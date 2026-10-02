@php
    $steps = [
        1 => __('booking.step_seats'),
        2 => __('booking.step_passengers'),
        3 => __('booking.step_checkout'),
    ];
@endphp

<div class="flex items-center justify-center gap-2 sm:gap-4 mb-8">
    @foreach($steps as $number => $label)
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold shrink-0
                    {{ $number < $current ? 'bg-sky text-white' : ($number === $current ? 'bg-amber text-navy' : 'bg-ink/10 text-ink/40') }}">
                    @if($number < $current)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    @else
                        {{ $number }}
                    @endif
                </span>
                <span class="text-sm font-semibold hidden sm:inline {{ $number === $current ? 'text-ink' : 'text-ink/40' }}">{{ $label }}</span>
            </div>
            @if($number < count($steps))
                <span class="w-6 sm:w-10 border-t border-dashed border-ink/20"></span>
            @endif
        </div>
    @endforeach
</div>
