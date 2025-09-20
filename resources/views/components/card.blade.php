<div>
   @props([
    'padding' => 'p-6',
])

<div {{ $attributes->merge([
    'class' => trim('bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg ' . $padding),
]) }}>
    {{ $slot }}
</div>