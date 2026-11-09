<x-admin :title="$user->exists ? 'Edit Staff' : 'Add Staff'" active="users">

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card max-w-xl p-6">
        @csrf
        @if($user->exists) @method('PUT') @endif
        @if($errors->any())<div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>@endif

        <div class="space-y-4">
            <div><label class="label">Name *</label><input name="name" class="input" required value="{{ old('name', $user->name) }}"></div>
            <div><label class="label">Email *</label><input type="email" name="email" class="input" required value="{{ old('email', $user->email) }}"></div>
            <div><label class="label">Phone</label><input name="phone" class="input" value="{{ old('phone', $user->phone) }}"></div>
            <div>
                <label class="label">Role *</label>
                <select name="role" class="input" required>
                    <option value="">— Select role —</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', $user->roles->pluck('name')->first())===$role->name)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">Password {{ $user->exists ? '(leave blank to keep)' : '*' }}</label><input type="password" name="password" class="input" {{ $user->exists ? '' : 'required' }}></div>
                <div><label class="label">Confirm Password</label><input type="password" name="password_confirmation" class="input"></div>
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" class="rounded text-gold-500" @checked(old('is_active', $user->is_active ?? true))> Active</label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-gold group"><span>{{ $user->exists ? 'Update' : 'Create' }}</span></button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>

</x-admin>
