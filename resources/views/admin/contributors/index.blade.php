<x-admin title="Contributors" active="contributors">

    <form method="GET" class="mb-5 flex gap-2">
        <input name="search" value="{{ request('search') }}" placeholder="Search name, email, code..." class="input w-72">
        <button class="btn-dark px-5">Search</button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Contributor</th>
                        <th class="px-5 py-3">Referral Code</th>
                        <th class="px-5 py-3">Total Earned</th>
                        <th class="px-5 py-3">Available</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contributors as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <p class="font-medium text-ink">{{ $c->name }}</p>
                                <p class="text-xs text-gray-400">{{ $c->email }} · {{ $c->phone }}</p>
                            </td>
                            <td class="px-5 py-3"><code class="rounded bg-gray-100 px-2 py-0.5 text-xs">{{ $c->referral_code }}</code></td>
                            <td class="px-5 py-3 font-medium text-emerald-600">{{ money($c->totalEarnings()) }}</td>
                            <td class="px-5 py-3 font-medium">{{ money($c->availableBalance()) }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-action-icon type="view" :href="route('admin.contributors.show', $c)" label="View contributor" color="gold" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-10 text-center text-gray-400">No contributors yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $contributors->links() }}</div>
    </div>

</x-admin>
