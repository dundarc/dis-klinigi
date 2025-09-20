@props(['level'])

@php
    // Gelen 'level' prop'unun bir Enum nesnesi olduğunu varsayıyoruz.
    // Önce null olup olmadığını kontrol edelim, sonra değerini alalım.
    $levelValue = $level?->value;

    $colors = [
        'red' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    ];
    $colorClass = $colors[$levelValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';

    $text = [
        'red' => 'Kritik',
        'yellow' => 'Acil',
        'green' => 'Normal',
    ];
    $levelText = $text[$levelValue] ?? 'Bilinmiyor';
@endphp

<span {{ $attributes->merge(['class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $colorClass]) }}>
    {{ $levelText }}
</span>