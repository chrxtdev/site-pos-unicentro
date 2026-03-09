@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 mt-2 mb-2 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm font-semibold transition duration-150 ease-in-out border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]'
            : 'inline-flex items-center px-4 py-2 mt-2 mb-2 rounded-lg text-gray-300 text-sm font-medium hover:text-white hover:bg-gray-800 border border-transparent transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
