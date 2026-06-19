@props(['paginator'])

@if($paginator->hasPages())
<nav class="flex items-center justify-between" x-data="{ jump: false }" aria-label="Pagination">
    <p class="text-sm text-slate-500 dark:text-slate-400 hidden sm:block">
        {{ __('Showing') }} <span class="font-medium text-slate-700 dark:text-slate-300">{{ $paginator->firstItem() }}</span>
        {{ __('to') }} <span class="font-medium text-slate-700 dark:text-slate-300">{{ $paginator->lastItem() }}</span>
        {{ __('of') }} <span class="font-medium text-slate-700 dark:text-slate-300">{{ $paginator->total() }}</span> {{ __('results') }}
    </p>
    <p class="text-sm text-slate-500 dark:text-slate-400 sm:hidden">
        {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} / {{ $paginator->total() }}
    </p>
    <div class="flex items-center gap-1">
        @if($paginator->onFirstPage())
        <span aria-hidden="true" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm text-slate-300 dark:text-slate-600 cursor-default">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous page" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        @endif

        <div class="hidden sm:flex items-center gap-1">
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors {{ $page === $paginator->currentPage() ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" @if($page === $paginator->currentPage()) aria-current="page" @endif>
                <span class="sr-only">Page </span>{{ $page }}
            </a>
            @endforeach
        </div>

        <button @click="jump = !jump" class="sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
            {{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}
        </button>

        <div x-show="jump" @click.away="jump = false" x-cloak x-transition class="absolute bottom-full mb-2 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-2 flex items-center gap-1">
            <span class="text-xs text-slate-500 dark:text-slate-400">Page</span>
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-1">
                @foreach(request()->except('page') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}" class="w-16 px-2 py-1 text-sm border border-slate-300 dark:border-slate-600 rounded text-center input">
                <button type="submit" class="btn-primary btn-sm">Go</button>
            </form>
        </div>

        @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next page" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @else
        <span aria-hidden="true" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm text-slate-300 dark:text-slate-600 cursor-default">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
        @endif
    </nav>
@endif
