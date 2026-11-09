<x-dashboard title="Profile" active="profile">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Profile info --}}
        <div class="card overflow-hidden">
            <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold-500/10 text-gold-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
                </span>
                <h2 class="font-serif text-lg font-semibold text-ink">Profile Information</h2>
            </div>
            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-4 p-6">
                @csrf @method('PATCH')
                <div>
                    <label class="label">Full Name</label>
                    <input name="name" class="input" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input name="phone" class="input" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label">Country</label><input name="country" class="input" value="{{ old('country', $user->country) }}"></div>
                    <div><label class="label">City</label><input name="city" class="input" value="{{ old('city', $user->city) }}"></div>
                </div>
                <div><label class="label">Address</label><input name="address" class="input" value="{{ old('address', $user->address) }}"></div>
                <button class="btn-gold group w-full"><span>Save Changes</span></button>
            </form>
        </div>

        {{-- Change password --}}
        <div class="card h-fit overflow-hidden">
            <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/10 text-brand-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                </span>
                <h2 class="font-serif text-lg font-semibold text-ink">Change Password</h2>
            </div>
            <form method="POST" action="{{ route('dashboard.password.update') }}" class="space-y-4 p-6">
                @csrf @method('PATCH')
                <div>
                    <label class="label">Current Password</label>
                    <input type="password" name="current_password" class="input" required>
                </div>
                <div>
                    <label class="label">New Password</label>
                    <input type="password" name="password" class="input" required>
                </div>
                <div>
                    <label class="label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="input" required>
                </div>
                <button class="btn-dark w-full">Update Password</button>
            </form>
        </div>
    </div>

</x-dashboard>
