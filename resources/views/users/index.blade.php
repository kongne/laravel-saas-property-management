@extends('layouts.app')
@section('title', __('Users'))

@php
    $avatarColors = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-sky-500', 'bg-rose-500', 'bg-violet-500', 'bg-cyan-500', 'bg-orange-500'];
    $userIdsJson = $users->getCollection()->pluck('id')->map(fn($id) => (string) $id)->toJson();
@endphp

@section('content')
@if($neverLoggedIn > 0)
<div class="flex items-center gap-3 p-4 mb-6 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-sm text-amber-700 dark:text-amber-300 flex-1">
        <strong>{{ $neverLoggedIn }}</strong> user(s) have never logged in.
        <a href="{{ route('users.index', ['status' => 'never']) }}" class="underline font-medium hover:text-amber-800 dark:hover:text-amber-200">{{ __('View them') }}</a>
    </p>
</div>
@endif

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('users.index') }}" class="card p-4 card-hover no-underline block hover:ring-1 hover:ring-indigo-300 dark:hover:ring-indigo-600 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Total Users') }}</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200">{{ $totalUsers }}</p>
            </div>
        </div>
    </a>
    <a href="{{ route('users.index', ['status' => 'active']) }}" class="card p-4 card-hover no-underline block hover:ring-1 hover:ring-emerald-300 dark:hover:ring-emerald-600 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Active') }}</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $activeUsers }}</p>
            </div>
        </div>
    </a>
    <a href="{{ route('users.index', ['status' => 'inactive']) }}" class="card p-4 card-hover no-underline block hover:ring-1 hover:ring-amber-300 dark:hover:ring-amber-600 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Inactive') }}</p>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $inactiveUsers }}</p>
            </div>
        </div>
    </a>
    <a href="{{ route('users.index', ['status' => 'never']) }}" class="card p-4 card-hover no-underline block hover:ring-1 hover:ring-slate-300 dark:hover:ring-slate-600 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Never Logged In') }}</p>
                <p class="text-2xl font-bold text-slate-600 dark:text-slate-400">{{ $neverLoggedIn }}</p>
            </div>
        </div>
    </a>
</div>

<div x-data="{ selected: [], selectAll() { this.selected.length === {{ $users->count() }} ? this.selected = [] : this.selected = JSON.parse('{{ $userIdsJson }}'); } }" class="card overflow-hidden">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('Users') }}</h3>
        <div class="flex items-center gap-2 flex-wrap">
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <input type="text" name="search" class="input !w-28 sm:!w-36 !py-1.5 !text-xs" placeholder="Search..." value="{{ request('search') }}">
                <select name="role" class="select !w-20 sm:!w-24 !py-1.5 !text-xs">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                    <option value="landlord" {{ request('role') === 'landlord' ? 'selected' : '' }}>{{ __('Landlord') }}</option>
                    <option value="tenant" {{ request('role') === 'tenant' ? 'selected' : '' }}>{{ __('Tenant') }}</option>
                </select>
                <select name="status" class="select !w-24 sm:!w-28 !py-1.5 !text-xs">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    <option value="never" {{ request('status') === 'never' ? 'selected' : '' }}>{{ __('Never Logged In') }}</option>
                </select>
                <input type="date" name="last_login_from" class="input !w-28 !py-1.5 !text-xs" value="{{ request('last_login_from') }}" title="From date">
                <input type="date" name="last_login_to" class="input !w-28 !py-1.5 !text-xs" value="{{ request('last_login_to') }}" title="To date">
                <button type="submit" class="btn-secondary btn-sm">{{ __('Filter') }}</button>
                <a href="{{ route('users.index') }}" class="btn-secondary btn-sm">{{ __('Reset') }}</a>
            </form>
            <a href="{{ route('users.export', request()->query()) }}" class="btn-secondary btn-sm" title="Export CSV">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('Export') }}
            </a>
        </div>
    </div>

    <div x-show="selected.length > 0" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="flex items-center gap-3 px-4 sm:px-6 py-2.5 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-200 dark:border-indigo-800 flex-wrap">
        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <span class="text-sm text-indigo-700 dark:text-indigo-300 flex-shrink-0">
            <span x-text="selected.length" class="font-bold"></span> user(s) selected
        </span>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('users.bulk-deactivate') }}" id="bulk-form">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="users[]" :value="id">
                </template>
                <button type="submit" class="btn-danger btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    {{ __('Deactivate All') }}
                </button>
            </form>
            <button @click="selected = []" class="btn-secondary btn-sm">Clear</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50">
                    <th scope="col" class="w-10 px-4 py-3">
                        <input type="checkbox" @click="selectAll()" :checked="selected.length === {{ $users->count() }} && {{ $users->count() }} > 0" class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800">
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('User') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Role') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Status') }}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Last Login') }}</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($users as $user)
                @php
                    $initial = strtoupper(substr($user->name, 0, 1));
                    $colorIdx = abs(crc32($user->name)) % 8;
                    $avatarColor = $avatarColors[$colorIdx];
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" :class="{ 'bg-indigo-50 dark:bg-indigo-900/10': selected.includes('{{ $user->id }}') }">
                    <td class="px-4 py-3">
                        <input type="checkbox" value="{{ $user->id }}" x-model="selected" class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $avatarColor }} flex items-center justify-center text-xs font-bold text-white flex-shrink-0 select-none">
                                {{ $initial }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-slate-800 dark:text-slate-200 truncate max-w-[120px] sm:max-w-[180px]">{{ $user->name }}</div>
                                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                    <span class="truncate max-w-[120px] sm:max-w-[180px]">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                    <svg class="w-3 h-3 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge whitespace-nowrap inline-flex items-center gap-1.5 {{
                            $user->role === 'admin' ? 'badge-danger' :
                            ($user->role === 'landlord' ? 'badge-info' :
                            'badge-neutral')
                        }}">
                            @if($user->role === 'admin')
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.235a.75.75 0 00.515 1.076 32.91 32.91 0 003.068.702m1.374.515a30.494 30.494 0 002.544.654 30.5 30.5 0 003.08-.654m-5.738 1.375c.588.147 1.194.26 1.813.337a5.25 5.25 0 003.626 0 30.16 30.16 0 011.813-.337m-6.252 1.375a26.957 26.957 0 003.504 0m-6.752-2.068A.75.75 0 0112 18.25V20.5" clip-rule="evenodd"/></svg>
                            @elseif($user->role === 'landlord')
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M12.232 4.232a3 3 0 014.536 3.036l-8.11 8.11-.774 2.322a.75.75 0 01-.949.492l-2.026-.676a.75.75 0 01-.495-.496l-.677-2.025a.75.75 0 01.493-.95l2.322-.774 8.11-8.11z"/></svg>
                            @else
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/></svg>
                            @endif
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-300 dark:shadow-emerald-700"></span>
                            {{ __('Active') }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-700 dark:text-red-400">
                            <span class="w-2 h-2 rounded-full bg-red-500 shadow-sm shadow-red-300 dark:shadow-red-700"></span>
                            {{ __('Inactive') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->last_login_at)
                        <div class="text-xs text-slate-600 dark:text-slate-400" title="{{ $user->last_login_at->format('M d, Y H:i') }}">
                            {{ $user->last_login_at->diffForHumans() }}
                        </div>
                        @else
                        <span class="inline-flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500 italic">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Never') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Edit user">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 {{ $user->is_active ? 'text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30' : 'text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30' }} rounded-md transition-colors" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($user->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                            @if($user->id !== Auth::id())
                            <x-confirm action="{{ route('users.destroy', $user) }}" method="DELETE" message="Delete this user permanently? This action cannot be undone." confirmText="{{ __('Delete') }}">
                                <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Delete user">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </x-confirm>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <x-empty-state message="{{ __('No users found.') }}" />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
        <x-pagination :paginator="$users" />
    </div>
    @endif
</div>
@endsection