<x-dashboard title="Payout / Bank Details" active="bank">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Add / update form --}}
        <div class="card overflow-hidden" x-data="{ type: 'easypaisa', sameNumber: false, phone: @js($user->phone) }">
            <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold-500/10 text-gold-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15l-.75 3.75H5.25L4.5 3Zm.75 3.75V21m13.5-14.25V21M8.25 10.5v6.75m3.75-6.75v6.75m3.75-6.75v6.75"/></svg>
                </span>
                <h2 class="font-serif text-lg font-semibold text-ink">Add Payout Method</h2>
            </div>

            <div class="p-6">
                @if($errors->any())
                    <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('dashboard.bank.save') }}" class="space-y-5">
                    @csrf

                    {{-- Type selector --}}
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['easypaisa' => 'EasyPaisa', 'jazzcash' => 'JazzCash', 'bank' => 'Bank'] as $val => $lbl)
                            <label class="cursor-pointer rounded-xl border-2 p-3 text-center text-sm font-medium transition"
                                   :class="type === '{{ $val }}' ? 'border-gold-400 bg-gold-200/20 text-ink shadow-sm' : 'border-gray-200 text-gray-500 hover:border-gray-300'">
                                <input type="radio" name="type" value="{{ $val }}" class="hidden" x-model="type">
                                {{ $lbl }}
                            </label>
                        @endforeach
                    </div>

                    {{-- EasyPaisa / JazzCash --}}
                    <template x-if="type === 'easypaisa' || type === 'jazzcash'">
                        <div class="space-y-3">
                            <div class="rounded-xl bg-brand-50 p-4 text-sm text-brand-800">
                                Your registered number is <strong x-text="phone || 'not set'"></strong>.
                                Is your <span x-text="type === 'easypaisa' ? 'EasyPaisa' : 'JazzCash'"></span> account on this number?
                            </div>
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" x-model="sameNumber" @change="$refs.acc.value = sameNumber ? phone : ''" class="rounded text-gold-500">
                                Yes, use my registered number
                            </label>
                            <div>
                                <label class="label">Account Number</label>
                                <input x-ref="acc" name="account_number" class="input" placeholder="03XXXXXXXXX" required value="{{ old('account_number') }}">
                            </div>
                        </div>
                    </template>

                    {{-- Bank --}}
                    <template x-if="type === 'bank'">
                        <div class="space-y-3">
                            <div>
                                <label class="label">IBAN Number</label>
                                <input name="iban" class="input" placeholder="PK00XXXX0000000000000000" value="{{ old('iban') }}">
                            </div>
                            <div>
                                <label class="label">Account Title</label>
                                <input name="account_title" class="input" value="{{ old('account_title', $user->name) }}">
                            </div>
                            <div>
                                <label class="label">Bank Name</label>
                                <input name="bank_name" class="input" placeholder="e.g. HBL, Meezan, UBL" value="{{ old('bank_name') }}">
                            </div>
                        </div>
                    </template>

                    <button class="btn-gold group w-full"><span>Save Payout Method</span></button>
                </form>
            </div>
        </div>

        {{-- Existing --}}
        <div class="card h-fit overflow-hidden">
            <div class="border-b border-gray-100 p-5"><h2 class="section-title">Saved Methods</h2></div>
            <div class="p-6">
                @if($banks->count())
                    <div class="space-y-3">
                        @foreach($banks as $b)
                            <div class="flex items-center justify-between rounded-xl border p-4 transition {{ $b->is_default ? 'border-gold-400 bg-gold-200/10 shadow-sm' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $b->is_default ? 'bg-gold-500/20 text-gold-700' : 'bg-gray-100 text-gray-500' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15l-.75 3.75H5.25L4.5 3Zm.75 3.75V21m13.5-14.25V21M8.25 10.5v6.75m3.75-6.75v6.75m3.75-6.75v6.75"/></svg>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-ink">{{ $b->label() }}</p>
                                        <p class="text-sm text-gray-500">{{ $b->summary() }}</p>
                                    </div>
                                </div>
                                @if($b->is_default)
                                    <span class="rounded-full bg-gold-500/20 px-3 py-1 text-xs font-semibold text-gold-700">Default</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center py-10 text-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15l-.75 3.75H5.25L4.5 3Zm.75 3.75V21m13.5-14.25V21M8.25 10.5v6.75m3.75-6.75v6.75m3.75-6.75v6.75"/></svg>
                        </span>
                        <p class="mt-3 text-sm text-gray-400">No payout method added yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-dashboard>
