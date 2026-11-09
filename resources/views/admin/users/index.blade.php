<x-admin title="Users / Staff" active="users">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Staff User?' } }">

    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.users.create') }}" class="btn-gold group"><span>+ Add Staff</span></a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                <tr><th class="px-5 py-3">Name</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Role</th><th class="px-5 py-3">Active</th><th class="px-5 py-3 text-right">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-ink">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @foreach($user->roles as $role)
                                <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-5 py-3">{!! $user->is_active ? '<span class="text-emerald-600">●</span>' : '<span class="text-red-400">●</span>' !!}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-action-icon type="edit" :href="route('admin.users.edit', $user)" label="Edit" color="gold" />
                                @unless($user->hasRole('super-admin'))
                                    <button type="button" title="Delete" aria-label="Delete"
                                            @click="confirm = { open:true, name: @js($user->name), action: @js(route('admin.users.destroy', $user)), title:'Delete Staff User?' }"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-10 text-center text-gray-400">No staff users.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
