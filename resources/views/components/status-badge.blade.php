@props(['status', 'color' => 'gray'])

@php
    $classes = [
        'amber'   => 'bg-amber-100 text-amber-700',
        'blue'    => 'bg-blue-100 text-blue-700',
        'indigo'  => 'bg-indigo-100 text-indigo-700',
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'red'     => 'bg-red-100 text-red-700',
        'gray'    => 'bg-gray-100 text-gray-700',
    ][$color] ?? 'bg-gray-100 text-gray-700';
@endphp

<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $classes }}">
    {{ str_replace('_', ' ', $status) }}
</span>
