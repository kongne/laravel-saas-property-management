@props(['variant' => 'text', 'count' => 1, 'class' => ''])

@php $count = max(1, (int) $count); @endphp

@if($variant === 'text')
    <div aria-hidden="true" class="space-y-2 {{ $class }}">
        @for($i = 0; $i < $count; $i++)
        <div class="skeleton-text"></div>
        @endfor
    </div>
@elseif($variant === 'text-sm')
    <div aria-hidden="true" class="space-y-1.5 {{ $class }}">
        @for($i = 0; $i < $count; $i++)
        <div class="skeleton-text-sm"></div>
        @endfor
    </div>
@elseif($variant === 'heading')
    <div aria-hidden="true" class="{{ $class }}"><div class="skeleton-heading"></div></div>
@elseif($variant === 'card')
    <div aria-hidden="true" class="card p-6 {{ $class }}">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="skeleton-heading"></div>
                <div class="skeleton-thumb w-12 h-12"></div>
            </div>
            <div class="skeleton-text w-1/2"></div>
            <div class="skeleton-text-sm"></div>
        </div>
    </div>
@elseif($variant === 'table-row')
    <div aria-hidden="true" class="flex items-center gap-4 px-4 py-3 border-b border-slate-100 dark:border-slate-700 {{ $class }}">
        @for($i = 0; $i < min($count, 6); $i++)
        <div class="skeleton-text flex-1"></div>
        @endfor
    </div>
@elseif($variant === 'table')
    <div aria-hidden="true" class="{{ $class }}">
        <div class="flex items-center gap-4 px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-t-lg">
            @for($i = 0; $i < min($count, 6); $i++)
            <div class="skeleton-heading flex-1"></div>
            @endfor
        </div>
        @for($row = 0; $row < min(5, $count); $row++)
        <div class="flex items-center gap-4 px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            @for($i = 0; $i < min(max(2, $count), 6); $i++)
            <div class="skeleton-text flex-1"></div>
            @endfor
            <div class="skeleton-thumb w-16 h-8"></div>
        </div>
        @endfor
    </div>
@elseif($variant === 'chart')
    <div aria-hidden="true" class="card p-6 {{ $class }}">
        <div class="skeleton-heading mb-4"></div>
        <div class="skeleton h-64 w-full rounded-lg"></div>
    </div>
@elseif($variant === 'avatar')
    <div aria-hidden="true" class="flex items-center gap-3 {{ $class }}">
        @for($i = 0; $i < min($count, 5); $i++)
        <div class="skeleton-avatar w-10 h-10"></div>
        @endfor
    </div>
@elseif($variant === 'image')
    <div aria-hidden="true" class="skeleton-thumb aspect-video w-full {{ $class }}"></div>
@endif
