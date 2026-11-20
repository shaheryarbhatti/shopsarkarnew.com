<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index()
    {
        $totalAll = (float) Expense::sum('amount');
        $totalMonth = (float) Expense::whereMonth('spent_on', now()->month)
            ->whereYear('spent_on', now()->year)
            ->sum('amount');

        return view('admin.expenses.index', [
            'categories' => Expense::CATEGORIES,
            'totalAll' => $totalAll,
            'totalMonth' => $totalMonth,
            'count' => Expense::count(),
        ]);
    }

    /** Server-side data source for the Yajra DataTable (with filters). */
    public function data(Request $request)
    {
        $query = Expense::query()
            ->with('creator')
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('spent_on', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('spent_on', '<=', $request->input('to')));

        // Total of the currently-filtered rows (shown above the table) — also
        // honours the DataTables global search box (title / note / category).
        $search = $request->input('search.value');
        $filteredTotal = (clone $query)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('title', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->sum('amount');

        $canManage = Auth::user()->can('expenses.manage');

        return DataTables::eloquent($query)
            ->editColumn('title', function (Expense $e) {
                $note = $e->note
                    ? '<p class="mt-0.5 text-xs text-gray-400">'.e(Str::limit($e->note, 60)).'</p>'
                    : '';

                return '<p class="font-medium text-ink">'.e($e->title).'</p>'.$note;
            })
            ->editColumn('category', function (Expense $e) {
                return $e->category
                    ? '<span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">'.e($e->category).'</span>'
                    : '<span class="text-gray-300">—</span>';
            })
            ->editColumn('spent_on', fn (Expense $e) => $e->spent_on->format('d M Y'))
            ->editColumn('amount', fn (Expense $e) => '<span class="font-semibold text-red-500">− '.money($e->amount).'</span>')
            ->addColumn('action', function (Expense $e) use ($canManage) {
                if (! $canManage) {
                    return '<span class="text-gray-300">—</span>';
                }

                $payload = htmlspecialchars(json_encode([
                    'id' => $e->id,
                    'title' => $e->title,
                    'category' => $e->category ?? '',
                    'amount' => (string) $e->amount,
                    'spent_on' => $e->spent_on->toDateString(),
                    'note' => $e->note ?? '',
                ]), ENT_QUOTES);

                $destroy = route('admin.expenses.destroy', $e);
                $name = e($e->title);

                return <<<HTML
                    <div class="flex items-center justify-end gap-1">
                        <button type="button" title="Edit" aria-label="Edit" class="js-edit-expense inline-flex h-9 w-9 items-center justify-center rounded-lg text-gold-600 transition hover:bg-gold-500/10 hover:text-gold-700" data-expense="{$payload}">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                        </button>
                        <button type="button" title="Delete" aria-label="Delete" class="js-delete-expense inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600" data-name="{$name}" data-action="{$destroy}">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        </button>
                    </div>
                    HTML;
            })
            ->filterColumn('title', function ($q, $keyword) {
                $q->where('title', 'like', "%{$keyword}%")->orWhere('note', 'like', "%{$keyword}%");
            })
            ->orderColumn('spent_on', 'spent_on $1, id $1')
            ->with('filtered_total', money($filteredTotal))
            ->rawColumns(['title', 'category', 'amount', 'action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_on' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['created_by'] = Auth::id();
        Expense::create($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense added successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_on' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update($data);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted.');
    }
}
