<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::with('creator')
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%")->orWhere('category', 'like', "%{$s}%"))
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->latest('spent_on')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $totalAll = (float) Expense::sum('amount');
        $totalMonth = (float) Expense::whereMonth('spent_on', now()->month)
            ->whereYear('spent_on', now()->year)
            ->sum('amount');

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'categories' => Expense::CATEGORIES,
            'totalAll' => $totalAll,
            'totalMonth' => $totalMonth,
        ]);
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
