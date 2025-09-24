@props(['href', 'title', 'description'])

<a href="{{ $href }}" class="block p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h5>
    <p class="font-normal text-gray-700 dark:text-gray-400">{{ $description }}</p>
</a>
