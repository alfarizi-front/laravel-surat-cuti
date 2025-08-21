@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg transform scale-105 transition-all duration-300'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50 hover:shadow-md transform hover:scale-105 transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
