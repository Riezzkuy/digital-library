@props(['type' => 'info', 'message'])

@php
    $alertClasses = [
        'info' => 'text-blue-800 border border-blue-300 bg-blue-50 dark:bg-gray-800 dark:text-blue-400',
        'danger' => 'text-red-800 border border-red-300 bg-red-50 dark:bg-gray-800 dark:text-red-400',
        'success' => 'text-green-800 border border-green-300 bg-green-50 dark:bg-gray-800 dark:text-green-400',
        'warning' => 'text-yellow-800 border border-yellow bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300',
        'dark' => 'text-gray-800 border border-gray-300 bg-gray-50 dark:bg-gray-800 dark:text-gray-300',
    ];
    $alertClass = $alertClasses[$type] ?? $alertClasses['info'];
@endphp

<div class="p-4 mb-4 text-sm rounded-lg {{ $alertClass }}" role="alert">
    {{ $message }}
</div>
