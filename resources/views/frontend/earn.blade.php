<x-storefront title="Earn With Us" footer="full"
    meta-description="Join the ShopSarkar affiliate program and earn commission on every sale through your referral link. Free sign-up, easy withdrawals to EasyPaisa, JazzCash or your bank."
    meta-keywords="ShopSarkar affiliate, earn money online Pakistan, perfume affiliate program, referral commission, work from home Pakistan">
    <section class="bg-brand-900 py-16 text-center text-white">
        <div class="container-x">
            <h1 class="font-serif text-4xl font-bold text-gold-400">Earn With Us</h1>
            <p class="font-urdu mt-2 text-2xl text-gold-300" dir="rtl">ہمارے ساتھ کمائیں</p>
            <p class="mx-auto mt-4 max-w-2xl text-white/70">{{ setting('affiliate_terms') }}</p>
        </div>
    </section>

    <section class="container-x py-14">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">
            {{-- How it works --}}
            <div>
                <h2 class="font-serif text-2xl font-bold text-ink">How it works</h2>
                <p class="font-urdu -mt-1 text-xl text-gold-600" dir="rtl">یہ کیسے کام کرتا ہے</p>

                <ol class="mt-6 space-y-6">
                    @foreach([
                        ['Sign up', 'Create your free contributor account in seconds.', 'سائن اپ کریں', 'چند سیکنڈوں میں اپنا مفت کنٹریبیوٹر اکاؤنٹ بنائیں۔'],
                        ['Share your link', 'Get a unique referral link to post on your social media.', 'اپنا لنک شیئر کریں', 'اپنے سوشل میڈیا پر پوسٹ کرنے کے لیے منفرد ریفرل لنک حاصل کریں۔'],
                        ['Earn commission', 'Earn a set commission on every sale made through your link.', 'کمیشن کمائیں', 'اپنے لنک سے ہونے والی ہر فروخت پر مقررہ کمیشن کمائیں۔'],
                        ['Withdraw', 'Withdraw your earnings to EasyPaisa, JazzCash or your bank.', 'رقم نکالیں', 'اپنی کمائی ایزی پیسہ، جاز کیش یا اپنے بینک میں نکالیں۔'],
                    ] as $i => $step)
                        <li class="flex gap-4">
                            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gold-500 font-bold text-brand-900">{{ $i+1 }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-ink">
                                    {{ $step[0] }}
                                    <span class="font-urdu ms-1.5 font-normal text-gold-600" dir="rtl">{{ $step[2] }}</span>
                                </p>
                                <p class="mt-0.5 text-sm text-gray-500">{{ $step[1] }}</p>
                                <p class="font-urdu text-sm leading-7 text-gray-400" dir="rtl">{{ $step[3] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Signup form --}}
            <div class="card p-8">
                <h2 class="mb-6 font-serif text-xl font-semibold text-ink">
                    Join the program
                    <span class="font-urdu ms-1.5 text-lg font-normal text-gold-600" dir="rtl">پروگرام میں شامل ہوں</span>
                </h2>

                @if($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('earn.store') }}" class="space-y-4" x-data="{ show:false }">
                    @csrf
                    <div>
                        <label class="label">Full Name <span class="text-red-500">*</span><span class="font-urdu ms-1.5 font-normal text-gray-400" dir="rtl">پورا نام</span></label>
                        <input name="name" class="input" required value="{{ old('name') }}">
                    </div>
                    <div>
                        <label class="label">Phone <span class="text-red-500">*</span><span class="font-urdu ms-1.5 font-normal text-gray-400" dir="rtl">فون نمبر</span></label>
                        <input name="phone" class="input" required value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="label">Email <span class="text-gray-400">(optional)</span><span class="font-urdu ms-1.5 font-normal text-gray-400" dir="rtl">ای میل</span></label>
                        <input type="email" name="email" class="input" value="{{ old('email') }}">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label">Password <span class="text-red-500">*</span><span class="font-urdu ms-1.5 font-normal text-gray-400" dir="rtl">پاس ورڈ</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" class="input pr-10" required>
                                <button type="button" @click="show=!show" :aria-label="show ? 'Hide password' : 'Show password'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="label">Confirm <span class="text-red-500">*</span><span class="font-urdu ms-1.5 font-normal text-gray-400" dir="rtl">تصدیق</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" class="input pr-10" required>
                                <button type="button" @click="show=!show" :aria-label="show ? 'Hide password' : 'Show password'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-gold group w-full">
                        <span>Start Earning</span>
                        <span class="font-urdu" dir="rtl">— کمائی شروع کریں</span>
                    </button>
                </form>

                <p class="mt-4 text-center text-sm text-gray-500">
                    Already a member? <a href="{{ route('login') }}" class="font-semibold text-gold-600 hover:underline">Login</a>
                    <span class="font-urdu ms-1 text-gray-400" dir="rtl">پہلے سے رکن ہیں؟</span>
                </p>
            </div>
        </div>
    </section>
</x-storefront>
