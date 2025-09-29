@props([
    'title' => '',
    'subtitle' => '',
    'icon' => '',
    'actions' => []
])

<div class="bg-gradient-to-r from-white via-blue-50/50 to-indigo-50/50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl p-6 mb-6 border border-gray-200/50 dark:border-gray-600/50 shadow-sm">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex items-center space-x-6">
            @if($icon)
                <div class="p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                    {!! $icon !!}
                </div>
            @endif
            <div>
                @if($title)
                    <h1 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900 dark:from-gray-100 dark:via-gray-200 dark:to-gray-100 bg-clip-text text-transparent">
                        {{ $title }}
                    </h1>
                @endif
                @if($subtitle)
                    <p class="text-gray-600 dark:text-gray-300 mt-2 text-lg">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>
        @if(!empty($actions))
            <div class="flex flex-col sm:flex-row gap-4 lg:gap-3 w-full lg:w-auto">
                @foreach($actions as $action)
                    <a href="{{ $action['url'] ?? '#' }}"
                       @if(isset($action['attributes'])) @foreach($action['attributes'] as $key => $value) {{ $key }}="{{ $value }}" @endforeach @endif
                       class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r {{ $action['gradient'] ?? 'from-blue-600 to-indigo-700' }} hover:{{ $action['hover'] ?? 'from-blue-700 hover:to-indigo-800' }} text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        @if(isset($action['icon']))
                            {!! $action['icon'] !!}
                        @endif
                        {{ $action['label'] ?? '' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>