@props(['sortable' => false, 'name' => '', 'label' => ''])

@php
    $isSorted = request()->input('sort') === $name;
    $direction = request()->input('direction', 'asc');
    $sortDir = $isSorted && $direction === 'asc' ? 'desc' : 'asc';
@endphp

<th
    {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider']) }}>
    @if ($sortable)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $name, 'direction' => $sortDir]) }}"
            class="group flex items-center space-x-1">
            <span>{{ $label }}</span>
            <span class="inline-flex">
                @if ($isSorted && $direction === 'asc')
                    <svg class="h-4 w-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                @elseif($isSorted && $direction === 'desc')
                    <svg class="h-4 w-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="h-4 w-4 text-gray-400 group-hover:text-primary-500" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                @endif
            </span>
        </a>
    @else
        <span>{{ $label }}</span>
    @endif
</th>
