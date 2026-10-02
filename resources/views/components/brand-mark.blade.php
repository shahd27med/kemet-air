@props(['size' => 'base'])

@php
    $sizes = [
        'sm'   => ['icon' => 'h-6 w-6', 'text' => 'text-lg'],
        'base' => ['icon' => 'h-7 w-7', 'text' => 'text-xl'],
        'lg'   => ['icon' => 'h-9 w-9', 'text' => 'text-2xl'],
    ];
    $s = $sizes[$size] ?? $sizes['base'];
@endphp

{{--
    Wing mark: three overlapping feather blades sweeping back from a single
    root point — a minimal nod to the Horus falcon, without illustrating a
    literal bird. Rendered in the accent gold everywhere it appears.
--}}
<span class="inline-flex items-center gap-2.5">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor" class="{{ $s['icon'] }} text-amber shrink-0" aria-hidden="true">
        <path d="M5 37L20.5 10.5 25 16.5 14 37z"/>
        <path d="M15 37L29 6.5 33.5 12.5 23 37z" opacity="0.72"/>
        <path d="M24 37L36.5 4 41 10 30.5 37z" opacity="0.5"/>
    </svg>
    <span class="font-display {{ $s['text'] }} leading-none tracking-tight" style="font-weight:800;">
        <span class="text-white">KEMET</span><span class="text-amber"> AIR</span>
    </span>
</span>
