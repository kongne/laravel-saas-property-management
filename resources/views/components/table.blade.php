@props([
    'title' => null,
    'headers' => [],
    'actions' => null,
    'sortable' => true,
    'compact' => false,
])

@php
    $currentSort = request('sort');
    $currentDir = request('dir', 'asc');
@endphp

<div class="card overflow-hidden">
    @if($title || $actions)
    <div class="card-header">
        @if($title)
        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $title }}</h3>
        @endif
        @if($actions)
        <div class="flex items-center gap-2 flex-wrap">{{ $actions }}</div>
        @endif
    </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            @if(count($headers))
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    @foreach($headers as $key => $label)
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap" @if($currentSort === $key) aria-sort="{{ $currentDir === 'asc' ? 'ascending' : 'descending' }}" @endif>
                        @if($sortable && is_string($key))
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'dir' => $currentSort === $key && $currentDir === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-slate-300 transition-colors no-underline">
                                {{ $label }}
                                @if($currentSort === $key)
                                <svg class="w-3 h-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($currentDir === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    @endif
                                </svg>
                                @endif
                            </a>
                        @else
                            {{ $label }}
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            @endif
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if(isset($footer))
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
        {{ $footer }}
    </div>
    @endif
</div>
