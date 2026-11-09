@props(['item', 'active'])

@php $isActive = $active === $item['key']; @endphp

<a href="{{ route($item['route']) }}"
   @class([
       'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200',
       'bg-gradient-to-r from-gold-500/20 to-gold-500/5 text-gold-700 shadow-sm ring-1 ring-gold-400/20' => $isActive,
       'text-gray-600 hover:bg-gray-100/80 hover:text-ink' => ! $isActive,
   ])>
    {{-- Active indicator bar --}}
    <span @class([
        'absolute left-0 top-1/2 h-6 w-1 -translate-y-1/2 rounded-full bg-gradient-to-b from-gold-400 to-gold-600 transition-all',
        'opacity-100' => $isActive,
        'opacity-0' => ! $isActive,
    ])></span>

    <span @class([
        'flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg transition-colors',
        'bg-gold-500 text-brand-900 shadow-sm shadow-gold-500/30' => $isActive,
        'bg-gray-100 text-gray-500 group-hover:bg-white group-hover:text-gold-600' => ! $isActive,
    ])>
        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
        </svg>
    </span>

    <span class="flex-1">{{ $item['label'] }}</span>

    <svg @class([
            'h-4 w-4 transition-all',
            'text-gold-500 opacity-100' => $isActive,
            'text-gray-300 opacity-0 group-hover:translate-x-0.5 group-hover:opacity-100' => ! $isActive,
         ]) fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
</a>
