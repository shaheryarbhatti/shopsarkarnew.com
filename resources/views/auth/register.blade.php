<x-auth page="register" title="Register" heading="Create Account" subheading="Join us and start shopping">

    @if($errors->any())
        <div class="mb-5 flex items-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4" x-data="{ show:false }">
        @csrf

        <div>
            <label class="label">Full Name <span class="urdu font-normal text-gray-400">— پورا نام</span></label>
            <input name="name" class="input" required autofocus value="{{ old('name') }}" placeholder="Your name">
        </div>

        <div>
            <label class="label">Email Address <span class="urdu font-normal text-gray-400">— ای میل</span></label>
            <input type="email" name="email" class="input" required value="{{ old('email') }}" placeholder="you@example.com">
        </div>

        <div>
            <label class="label">Phone <span class="font-normal text-gray-400">(optional)</span> <span class="urdu font-normal text-gray-400">— فون نمبر</span></label>
            <input name="phone" class="input" value="{{ old('phone') }}" placeholder="03XX-XXXXXXX">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label">Password <span class="urdu font-normal text-gray-400">— پاس ورڈ</span></label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" class="input pr-10" required placeholder="••••••••">
                    <button type="button" @click="show=!show" :aria-label="show ? 'Hide password' : 'Show password'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="label">Confirm <span class="urdu font-normal text-gray-400">— تصدیق</span></label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" class="input pr-10" required placeholder="••••••••">
                    <button type="button" @click="show=!show" :aria-label="show ? 'Hide password' : 'Show password'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-gold group w-full py-3.5 text-base"><span>Create Account</span>
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-gold-600 hover:underline">Login</a>
    </p>

</x-auth>
