@props(['title', 'value', 'icon', 'color' => 'primary'])

@php
$colorClasses = match ($color) {
    'blue' => ['bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-950/30 border-blue-200 dark:border-blue-800/50', 'text-blue-600 dark:text-blue-400', 'bg-blue-100 dark:bg-blue-900/50'],
    'yellow' => ['bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-950/30 border-yellow-200 dark:border-yellow-800/50', 'text-yellow-600 dark:text-yellow-400', 'bg-yellow-100 dark:bg-yellow-900/50'],
    'red' => ['bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-950/30 border-red-200 dark:border-red-800/50', 'text-red-600 dark:text-red-400', 'bg-red-100 dark:bg-red-900/50'],
    'green' => ['bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-950/30 border-green-200 dark:border-green-800/50', 'text-green-600 dark:text-green-400', 'bg-green-100 dark:bg-green-900/50'],
    'purple' => ['bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-950/30 border-purple-200 dark:border-purple-800/50', 'text-purple-600 dark:text-purple-400', 'bg-purple-100 dark:bg-purple-900/50'],
    default => ['bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800/30 dark:to-gray-900/30 border-gray-200 dark:border-gray-700/50', 'text-primary-600 dark:text-primary-400', 'bg-primary-100 dark:bg-primary-900/50'],
};
@endphp

<div class="p-5 rounded-xl border shadow-sm transition-all duration-300 hover:shadow-md {{ $colorClasses[0] }}">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $title }}</p>
            <p class="mt-1.5 text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
        <div class="flex-shrink-0 p-3 rounded-full {{ $colorClasses[2] }}">
            {{-- Use Blade Heroicon Component --}}
            @svg($icon, 'w-6 h-6 ' . $colorClasses[1])
        </div>
    </div>
</div>