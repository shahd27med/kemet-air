@props(['type' => 'success', 'message' => null])

@if($message)
    @php
        $styles = [
            'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
            'error' => 'bg-red-50 border-red-200 text-red-700',
            'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        ][$type] ?? 'bg-sky-50 border-sky-200 text-sky-800';

        $icon = [
            'success' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'error' => 'M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'warning' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        ][$type] ?? '';
    @endphp

    <div x-data="{ show: true }" x-show="show" x-transition
         class="mb-6 flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm shadow-sm {{ $styles }}" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5 mt-0.5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
        </svg>
        <p class="flex-1 font-medium">{{ $message }}</p>
        <button @click="show = false" type="button" class="shrink-0 opacity-60 hover:opacity-100" aria-label="Dismiss">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif
