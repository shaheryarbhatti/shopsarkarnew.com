<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ setting('site_name','Shop') }}</title>
    <link rel="icon" href="{{ setting_file('favicon') ?: asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-brand-900 p-4">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <span class="font-serif text-3xl font-bold uppercase tracking-[0.2em] text-gold-400">{{ setting('site_name','Shop') }}</span>
            <p class="mt-1 text-sm text-gold-200/50">Admin Panel</p>
        </div>
        <div class="rounded-2xl bg-white p-8 shadow-2xl">
            <h1 class="mb-6 text-center font-serif text-xl font-bold text-ink">Sign in to continue</h1>

            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" class="input" required autofocus value="{{ old('email') }}">
                </div>
                <div>
                    <label class="label">Password</label>
                    <input type="password" name="password" class="input" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded text-gold-500"> Remember me
                </label>
                <button class="btn-gold group w-full"><span>Login</span></button>
            </form>
        </div>
        <p class="mt-5 text-center text-xs text-gold-200/40">© {{ date('Y') }} {{ setting('site_name','Shop') }}</p>
    </div>
</body>
</html>
