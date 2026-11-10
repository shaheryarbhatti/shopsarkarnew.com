<x-admin title="Settings" active="settings">

    @php
        $meta = [
            'general'   => ['Store Identity', 'Name, logo, currency & contact details', 'M3.75 21h16.5M4.5 3h15l-.75 3.75H5.25L4.5 3Zm.75 3.75V21m13.5-14.25V21M8.25 10.5v6.75m3.75-6.75v6.75m3.75-6.75v6.75'],
            'hero'      => ['Homepage Hero', 'Headings, background video & call to action', 'M2.25 12.75V12a9 9 0 1 1 18 0v.75M18 12a6 6 0 0 0-12 0M12 8.25v8.25m0 0 3-3m-3 3-3-3'],
            'shipping'  => ['Shipping', 'Flat rate & free-shipping threshold', 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12'],
            'tax'       => ['Tax', 'Enable tax and set the rate', 'M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185ZM9.75 9h.008v.008H9.75V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008V13.5Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z'],
            'affiliate' => ['Affiliate Program', 'Commissions, withdrawals & maturity', 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
            'reviews'   => ['Review Videos', 'Logo watermark on your YouTube review videos', 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6'],
            'marketing' => ['Marketing & Pixels', 'Facebook Pixel, TikTok Pixel & tracking codes', 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'],
        ];
        $labels = [
            'site_name'=>'Site Name','site_logo'=>'Header Logo','footer_logo'=>'Footer Logo','favicon'=>'Favicon (auto 512×512)','currency_symbol'=>'Currency Symbol',
            'contact_email'=>'Contact Email','contact_phone'=>'Contact Phone','footer_about'=>'Footer About Text',
            'hero_eyebrow'=>'Hero Top Text (above heading)',
            'hero_heading_en'=>'Hero Heading (English)','hero_heading_ur'=>'Hero Heading (Urdu)',
            'hero_subtext_en'=>'Hero Subtext (English)','hero_subtext_ur'=>'Hero Subtext (Urdu)',
            'hero_button_text'=>'Button Text','hero_button_text_ur'=>'Button Text (Urdu)',
            'hero_video'=>'Background Video (mp4/webm)','hero_poster'=>'Poster Image (desktop · landscape)','hero_poster_mobile'=>'Mobile Poster Image (portrait · phones)','hero_product_id'=>'Buy Now → Product',
            'shipping_enabled'=>'Enable Shipping','shipping_flat_rate'=>'Flat Shipping Rate (Rs.)',
            'free_shipping_enabled'=>'Enable Free Shipping','free_shipping_threshold'=>'Free Shipping Above (Rs.)',
            'tax_enabled'=>'Enable Tax','tax_rate'=>'Tax Rate (%)','tax_label'=>'Tax Label',
            'affiliate_enabled'=>'Enable Affiliate Program','withdraw_min_amount'=>'Minimum Withdrawal (Rs.)',
            'earning_maturity_days'=>'Earning Maturity (days)','affiliate_terms'=>'Program Description',
            'youtube_watermark'=>'Watermark Logo (transparent PNG, ≤1MB)','youtube_watermark_position'=>'Watermark Corner',
            'facebook_pixel_id'=>'Facebook Pixel ID','tiktok_pixel_id'=>'TikTok Pixel ID','custom_head_code'=>'Custom Head Code (advanced)',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px_1fr]">
        {{-- Vertical nav --}}
        <aside class="card h-fit overflow-hidden p-2">
            @foreach($groups as $g)
                <a href="{{ route('admin.settings.edit', $g) }}"
                   class="flex items-start gap-3 rounded-xl px-3 py-3 transition {{ $group===$g ? 'bg-brand-900 text-white' : 'hover:bg-gray-50' }}">
                    <span class="mt-0.5 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg {{ $group===$g ? 'bg-gold-500 text-brand-900' : 'bg-gray-100 text-gray-500' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta[$g][2] }}"/></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold {{ $group===$g ? 'text-white' : 'text-ink' }}">{{ $meta[$g][0] }}</span>
                        <span class="block text-xs {{ $group===$g ? 'text-gold-200/70' : 'text-gray-400' }}">{{ $meta[$g][1] }}</span>
                    </span>
                </a>
            @endforeach
        </aside>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data" class="card overflow-hidden">
            @csrf
            <div class="card-head">
                <span class="accent-bar"></span>
                <div>
                    <h2 class="font-serif text-lg font-semibold text-ink">{{ $meta[$group][0] }}</h2>
                    <p class="text-sm text-gray-400">{{ $meta[$group][1] }}</p>
                </div>
            </div>

            <div class="space-y-5 p-6">
                @if($errors->any())<div class="rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>@endif

                @if($group === 'reviews')
                    <div class="flex items-start gap-3 rounded-xl border border-gold-200 bg-gold-50/60 p-4 text-sm text-gray-600">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gold-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                        <span>
                            Upload a <strong>transparent PNG logo</strong> and pick a corner, then press <strong>Save</strong>. The logo is set as a branding watermark on your connected <strong>YouTube channel</strong>, so it appears in that corner of <strong>every</strong> video — including customer review videos you upload from the Reviews page. Removing the logo clears it from the channel.
                        </span>
                    </div>
                @endif

                @if($group === 'marketing')
                    <div class="flex items-start gap-3 rounded-xl border border-gold-200 bg-gold-50/60 p-4 text-sm text-gray-600">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gold-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                        <span>
                            Just paste your <strong>Pixel ID</strong> (e.g. <code class="rounded bg-white px-1">1234567890</code>) — the full tracking script is generated and loaded automatically in the site header on every page. Leave a field empty to disable it.
                        </span>
                    </div>
                @endif

                @foreach($fields as $key => $type)
                    <div>
                        <label class="label">{{ $labels[$key] ?? $key }}</label>

                        @if($key === 'custom_head_code')
                            <textarea name="custom_head_code" rows="6" class="input" placeholder="Paste any extra <script> or <meta> tags to inject into every page's &lt;head&gt;">{{ $values[$key] ?? '' }}</textarea>
                            <p class="mt-1 text-xs text-gray-400">Advanced — raw code added to the site header. Leave empty if unsure.</p>

                        @elseif($key === 'facebook_pixel_id')
                            <input name="facebook_pixel_id" class="input max-w-sm" value="{{ $values[$key] ?? '' }}" placeholder="e.g. 123456789012345">

                        @elseif($key === 'tiktok_pixel_id')
                            <input name="tiktok_pixel_id" class="input max-w-sm" value="{{ $values[$key] ?? '' }}" placeholder="e.g. C9XXXXXXXXXXXXXXXXXX">

                        @elseif($key === 'hero_product_id')
                            <select name="hero_product_id" class="input">
                                <option value="">— None —</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" @selected((string)($values[$key] ?? '')===(string)$p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>

                        @elseif($key === 'youtube_watermark_position')
                            @php $pos = $values[$key] ?? 'topRight'; @endphp
                            <select name="youtube_watermark_position" class="input max-w-xs">
                                @foreach(['topRight'=>'Top Right','topLeft'=>'Top Left','bottomRight'=>'Bottom Right','bottomLeft'=>'Bottom Left'] as $val => $lbl)
                                    <option value="{{ $val }}" @selected($pos===$val)>{{ $lbl }}</option>
                                @endforeach
                            </select>

                        @elseif($type === 'boolean')
                            @php $on = ($values[$key] ?? '0')==='1' || ($values[$key] ?? false)===true; @endphp
                            <label class="flex cursor-pointer items-center gap-3" x-data="{ on: {{ $on ? 'true' : 'false' }} }">
                                <input type="checkbox" name="{{ $key }}" value="1" class="peer sr-only" x-model="on" @checked($on)>
                                <span class="relative h-6 w-11 rounded-full bg-gray-300 transition peer-checked:bg-gold-500" :class="on && 'bg-gold-500'">
                                    <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition" :class="on && 'translate-x-5'"></span>
                                </span>
                                <span class="text-sm text-gray-600" x-text="on ? 'Enabled' : 'Disabled'"></span>
                            </label>

                        @elseif($type === 'textarea')
                            <textarea name="{{ $key }}" rows="3" class="input {{ str_contains($key,'_ur') ? 'urdu' : '' }}">{{ $values[$key] ?? '' }}</textarea>

                        @elseif($type === 'file')
                            @php $current = ($values[$key] ?? null) ? Storage::url($values[$key]) : null; @endphp
                            <div class="flex items-center gap-4" x-data="{ removed:false }">
                                @if($current)
                                    <input type="hidden" name="remove_{{ $key }}" :value="removed ? '1' : '0'">
                                    <div x-show="!removed" class="relative">
                                        @if($key === 'hero_video')
                                            <video src="{{ $current }}" class="h-24 rounded-lg ring-1 ring-black/10" controls muted></video>
                                        @else
                                            <img src="{{ $current }}" class="h-20 w-20 rounded-lg object-cover ring-1 ring-black/10">
                                        @endif
                                        <button type="button" @click="removed=true" title="Remove"
                                                class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white shadow hover:bg-red-700">✕</button>
                                    </div>
                                    <div x-show="removed" x-cloak class="flex items-center gap-2 text-sm text-red-500">
                                        <span>Will be removed on save.</span>
                                        <button type="button" @click="removed=false" class="font-semibold text-gray-500 underline">Undo</button>
                                    </div>
                                @endif
                                <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-gray-200 px-4 py-6 text-center text-sm text-gray-500 transition hover:border-gold-400 hover:bg-gold-50/40">
                                    <input type="file" name="{{ $key }}" accept="{{ $key==='hero_video' ? 'video/*' : 'image/*' }}" class="hidden" onchange="this.parentNode.querySelector('span').textContent=this.files[0]?.name || 'Click to upload'">
                                    <svg class="mx-auto mb-1 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                    <span>Click to upload {{ $key==='hero_video' ? 'video' : 'image' }}</span>
                                </label>
                            </div>

                        @elseif($type === 'number')
                            <input type="number" step="any" name="{{ $key }}" class="input max-w-xs" value="{{ $values[$key] ?? '' }}">

                        @else
                            <input name="{{ $key }}" class="input {{ str_contains($key,'_ur') ? 'urdu' : '' }}" value="{{ $values[$key] ?? '' }}">
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-6 py-4">
                <button class="btn-gold group"><span>Save Changes</span></button>
            </div>
        </form>
    </div>

</x-admin>
