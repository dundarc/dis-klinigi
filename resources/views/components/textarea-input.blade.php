@props([
    'id' => null,
    'name',
    'rows' => 3,
    'value' => '',
])

<textarea id="{{ $id ?? $name }}"
          name="{{ $name }}"
          rows="{{ $rows }}"
          {{ $attributes->merge([
              'class' => 'w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 
                          dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 
                          focus:ring-indigo-500 sm:text-sm'
          ]) }}>{{ old($name, $value) }}</textarea>
