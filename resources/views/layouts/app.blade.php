<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}" x-data="{ dark: localStorage.getItem('dark') === 'true' || (!localStorage.getItem('dark') && window.matchMedia('(prefers-color-scheme: dark)').matches), sidebarOpen: false, searchOpen: false, sidebarCollapsed: false }" :class="dark ? 'dark' : ''" x-init="localStorage.setItem('dark', dark); $watch('dark', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ config('app.name') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-['Inter'] bg-slate-100 antialiased" :class="sidebarOpen ? 'overflow-hidden md:overflow-auto' : ''">

    @auth
    {{-- Overlay --}}
    <div x-cloak x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition.opacity></div>

    {{-- Global Search Modal --}}
    <div x-cloak x-show="searchOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh]" x-transition>
        <div @@click="searchOpen = false" class="fixed inset-0 bg-black/50"></div>
        <div class="relative w-full max-w-2xl mx-4 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden" x-data="globalSearch()" @@keydown.escape="closeSearch()">
            <div class="flex items-center gap-3 px-4 border-b border-slate-200">
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-ref="searchInput" type="text" x-model="query" @@input="debouncedSearch = setTimeout(() => search(), 300)" @@keydown="onKeydown" placeholder="Search properties, tenants, leases, payments..." class="flex-1 py-3.5 text-sm border-0 focus:ring-0 outline-none" autocomplete="off">
                <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-xs text-slate-400 bg-slate-100 rounded border border-slate-200">ESC</kbd>
            </div>
            <div class="max-h-96 overflow-y-auto scrollbar-thin">
                <template x-if="loading">
                    <div class="flex items-center justify-center py-8">
                        <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </template>
                <template x-if="!loading && query.length >= 2 && results.length === 0">
                    <div class="flex flex-col items-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm">No results found for "<span x-text="query"></span>"</p>
                    </div>
                </template>
                <template x-if="!loading && query.length < 2 && !searching">
                    <div class="flex items-center gap-2 px-4 py-3 text-xs text-slate-400 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                        Type at least 2 characters to search across all modules
                    </div>
                </template>
                <template x-for="(result, index) in results" :key="index">
                    <a :href="result.url" @@click="closeSearch()" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors" :class="{ 'bg-indigo-50': index === selectedIndex }" @@mouseenter="selectedIndex = index">
                        <div :class="'w-8 h-8 rounded-lg flex items-center justify-center ' + getResultBadge(result.type)" x-html="getResultIcon(result.type)"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate" x-text="result.label"></p>
                            <p class="text-xs text-slate-500 truncate" x-text="result.subtext"></p>
                        </div>
                        <span class="text-xs font-medium capitalize text-slate-400" x-text="result.type"></span>
                    </a>
                </template>
            </div>
            <div class="hidden sm:flex items-center gap-3 px-4 py-2 border-t border-slate-200 text-xs text-slate-400">
                <span class="flex items-center gap-1"><kbd class="px-1 py-0.5 bg-slate-100 rounded border text-[10px]">&uarr;</kbd><kbd class="px-1 py-0.5 bg-slate-100 rounded border text-[10px]">&darr;</kbd> {{ __('Navigate') }}</span>
                <span class="flex items-center gap-1"><kbd class="px-1 py-0.5 bg-slate-100 rounded border text-[10px]">&#9166;</kbd> Open</span>
                <span class="flex items-center gap-1"><kbd class="px-1 py-0.5 bg-slate-100 rounded border text-[10px]">ESC</kbd> Close</span>
            </div>
        </div>
    </div>

    {{-- Flex layout: sidebar + content side by side on desktop --}}
    <div class="md:flex md:min-h-screen">
    <aside class="sidebar-main bg-slate-900 text-white flex flex-col transition-all duration-300 ease-in-out shadow-xl" :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0', sidebarCollapsed ? 'w-16' : 'w-64']">

        <div class="flex items-center h-16 border-b border-slate-700/50" :class="sidebarCollapsed ? 'justify-center' : 'justify-between px-4'">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 text-white no-underline group" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center group-hover:bg-indigo-400 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div x-show="!sidebarCollapsed">
                    <span class="font-semibold text-base">{{ config('app.name') }}</span>
                    <span class="block text-[10px] text-indigo-300 uppercase tracking-wider">{{ __('Management') }}</span>
                </div>
            </a>
            <button @@click="sidebarOpen = false" x-show="!sidebarCollapsed" class="md:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin">
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard">{{ __('Dashboard') }}</x-nav-link>

            @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
            <div x-show="!sidebarCollapsed" class="sidebar-section">{{ __('Management') }}</div>
            <x-nav-link href="{{ route('properties.index') }}" :active="request()->routeIs('properties.*')" icon="building">{{ __('Properties') }}</x-nav-link>
            <x-nav-link href="{{ route('units.index') }}" :active="request()->routeIs('units.*')" icon="door">{{ __('Units') }}</x-nav-link>
            <x-nav-link href="{{ route('tenants.index') }}" :active="request()->routeIs('tenants.*')" icon="people">{{ __('Tenants') }}</x-nav-link>
            <x-nav-link href="{{ route('leases.index') }}" :active="request()->routeIs('leases.*')" icon="document">{{ __('Leases') }}</x-nav-link>
            <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="users">{{ __('Users') }}</x-nav-link>
            @endif

            <div x-show="!sidebarCollapsed" class="sidebar-section">{{ __('Finance') }}</div>
            <x-nav-link href="{{ route('payments.index') }}" :active="request()->routeIs('payments.*')" icon="credit-card">{{ __('Payments') }}</x-nav-link>
            <x-nav-link href="{{ route('billing.index') }}" :active="request()->routeIs('billing.*')" icon="currency-dollar">{{ __('Billing') }}</x-nav-link>

            <div x-show="!sidebarCollapsed" class="sidebar-section">{{ __('Services') }}</div>
            <x-nav-link href="{{ route('maintenance.index') }}" :active="request()->routeIs('maintenance.*')" icon="tools">{{ __('Maintenance') }}</x-nav-link>
            <x-nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.*')" icon="bell" :badge="Auth::user()->unreadNotifications->count() > 0 ? Auth::user()->unreadNotifications->count() : null">{{ __('Notifications') }}</x-nav-link>
            <x-nav-link href="{{ route('audit.index') }}" :active="request()->routeIs('audit.*')" icon="clock">{{ __('Audit Log') }}</x-nav-link>

            <div x-show="!sidebarCollapsed" class="sidebar-section">{{ __('Links') }}</div>
            <a href="{{ route('home') }}" class="sidebar-link sidebar-link-inactive" target="_blank">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span x-show="!sidebarCollapsed" class="flex-1">{{ __('View Website') }}</span>
            </a>
        </nav>

        <div class="border-t border-slate-700/50 p-3" x-data="{ open: false }" x-effect="sidebarCollapsed && (open = false)">
            <button @@click="open = !open" class="flex items-center w-full px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center' : 'gap-3'">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold shadow-sm flex-shrink-0">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div x-show="!sidebarCollapsed" class="flex-1 text-left min-w-0">
                    <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</div>
                </div>
                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @@click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" class="mt-1 py-1 bg-slate-800/80 rounded-lg border border-slate-700/50">
                <div class="px-3 py-2 text-xs text-slate-400">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-indigo-400' : (Auth::user()->role === 'landlord' ? 'bg-emerald-400' : 'bg-amber-400') }}"></span>
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
                <a href="{{ route('profile.security') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ __('Security') }}
                </a>
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    {{ __('Notifications') }}
                    @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <hr class="border-slate-700/50 my-1">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-300 hover:text-red-200 hover:bg-slate-700 transition-colors rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('Sign out') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-h-screen">
        @auth
        {{-- Top Header Bar --}}
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-4 h-14">
                <div class="flex items-center gap-3">
                    <button @@click="window.innerWidth >= 768 ? (sidebarCollapsed = !sidebarCollapsed) : (sidebarOpen = !sidebarOpen)" class="text-slate-600 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <button data-global-search @@click="searchOpen = true" class="hidden sm:flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors min-w-[240px] border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>{{ __('Search anything...') }}</span>
                        <kbd class="ml-auto flex items-center gap-0.5 text-[10px] text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200 shadow-sm">
                            <span>Ctrl</span><span>K</span>
                        </kbd>
                    </button>
                    <button @@click="searchOpen = true" class="sm:hidden text-slate-600 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-1">
                    <div x-data="{ open: false }" class="relative">
                        <button @@click="open = !open" @@click.away="open = false" class="flex items-center gap-1 px-2 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="hidden sm:inline">{{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="w-3 h-3" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" class="absolute right-0 mt-1 py-1 w-32 bg-white rounded-lg shadow-lg border border-slate-200 z-50">
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors {{ app()->getLocale() === 'en' ? 'font-semibold text-indigo-600' : '' }}">{{ __('English') }}</a>
                            <a href="{{ route('locale.switch', 'fr') }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors {{ app()->getLocale() === 'fr' ? 'font-semibold text-indigo-600' : '' }}">{{ __('French') }}</a>
                            <a href="{{ route('locale.switch', 'es') }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors {{ app()->getLocale() === 'es' ? 'font-semibold text-indigo-600' : '' }}">{{ __('Spanish') }}</a>
                            <a href="{{ route('locale.switch', 'ar') }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors {{ app()->getLocale() === 'ar' ? 'font-semibold text-indigo-600' : '' }}">{{ __('Arabic') }}</a>
                        </div>
                    </div>
                    <button @@click="dark = !dark" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors" :title="dark ? 'Light mode' : 'Dark mode'">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <a href="{{ route('notifications.index') }}" class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <div class="hidden md:flex items-center gap-2 pl-2 border-l border-slate-200">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <span class="text-sm text-slate-700 font-medium">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">
            {{-- Flash Messages --}}
            @if(session('success'))
            <x-toast type="success" :message="session('success')" />
            @endif
            @if(session('error'))
            <x-toast type="error" :message="session('error')" />
            @endif
            @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div x-data="{ show: true }" class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg shadow-sm" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <strong class="text-sm">{{ __('Please fix the following errors:') }}</strong>
                    <button @@click="show = !show" class="ml-auto text-red-500 dark:text-red-400 hover:text-red-700 p-0.5 rounded hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
                        <svg class="w-4 h-4 transition-transform" :class="show ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <ul x-show="show" class="mt-2 list-disc pl-5 text-sm space-y-0.5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="animate-fade-in">
            @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @auth
        <footer class="border-t border-slate-200 bg-white px-4 md:px-6 lg:px-8 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between text-xs text-slate-400">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</span>
                <span>v1.0</span>
            </div>
        </footer>
        @endauth
    </div>
    </div>{{-- /md:flex --}}
    @endauth

    {{-- Scroll to Top --}}
    <button
        x-data="{ visible: false }"
        x-init="window.addEventListener('scroll', () => visible = window.scrollY > 300)"
        x-show="visible"
        x-cloak
        @@click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-6 right-6 z-50 w-10 h-10 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition-all flex items-center justify-center"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        aria-label="Scroll to top"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    @stack('scripts')
    @vite('resources/js/app.js')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }

        if (!navigator.onLine) {
            document.body.innerHTML = '<div class="flex items-center justify-center min-h-screen bg-slate-100 p-4"><div class="text-center max-w-sm"><div class="w-20 h-20 bg-slate-200 rounded-full inline-flex items-center justify-center mb-6"><svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m-2.829-2.829a5 5 0 000-7.07m-4.243 4.243a1 1 0 010-1.414"/><circle cx="12" cy="12" r="1" fill="currentColor"/></svg></div><h2 class="text-xl font-bold text-slate-800 mb-2">You\'re Offline</h2><p class="text-slate-500 mb-6">Please check your connection.</p><button onclick="location.reload()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Try Again</button></div></div>';
        }

        window.addEventListener('online', () => location.reload());
    </script>
</body>
</html>
