@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center text-md font-medium leading-5 text-violet-500 focus:outline-none w-full h-12 active transition duration-150 ease-in-out'
    : 'inline-flex items-center text-md font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>