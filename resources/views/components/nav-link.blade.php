@props(['active' => false])

@php
$classes = ($active) 
            ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1'
            : 'text-gray-600 hover:text-blue-600 font-medium';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
