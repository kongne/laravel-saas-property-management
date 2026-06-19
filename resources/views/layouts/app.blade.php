<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e293b">
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-['Inter'] bg-slate-100 antialiased" :class="sidebarOpen ? 'overflow-hidden md:overflow-auto' : ''">

    @auth
    <div x-cloak x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition.opacity></div>

    <aside class="fixed md:sticky top-0 left-0 z-40 h-screen w-64 bg-slate-900 text-white flex flex-col transform transition-transform duration-300 ease-in-out"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        <div class="flex items-center justify-between px-4 h-16 border-b border-slate-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white no-underline">
                <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="font-semibold text-lg">{{ config('app.name') }}</span>
            </a>
            <button @@click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard">Dashboard</x-nav-link>

            @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
            <div class="text-xs font-semibold uppercase text-slate-500 tracking-wider px-3 pt-4 pb-2">Management</div>
            <x-nav-link href="{{ route('properties.index') }}" :active="request()->routeIs('properties.*')" icon="building">Properties</x-nav-link>
            <x-nav-link href="{{ route('units.index') }}" :active="request()->routeIs('units.*')" icon="door">Units</x-nav-link>
            <x-nav-link href="{{ route('tenants.index') }}" :active="request()->routeIs('tenants.*')" icon="people">Tenants</x-nav-link>
            <x-nav-link href="{{ route('leases.index') }}" :active="request()->routeIs('leases.*')" icon="document">Leases</x-nav-link>
            <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="users">Users</x-nav-link>
            @endif

            <div class="text-xs font-semibold uppercase text-slate-500 tracking-wider px-3 pt-4 pb-2">Finance</div>
            <x-nav-link href="{{ route('payments.index') }}" :active="request()->routeIs('payments.*')" icon="credit-card">Payments</x-nav-link>

            <div class="text-xs font-semibold uppercase text-slate-500 tracking-wider px-3 pt-4 pb-2">Services</div>
            <x-nav-link href="{{ route('maintenance.index') }}" :active="request()->routeIs('maintenance.*')" icon="tools">Maintenance</x-nav-link>
            <x-nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.*')" icon="bell" :badge="Auth::user()->unreadNotifications->count() > 0 ? Auth::user()->unreadNotifications->count() : null">Notifications</x-nav-link>
        </nav>

        <div class="border-t border-slate-700 p-3" x-data="{ open: false }">
            <button @@click="open = !open" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="flex-1 text-left min-w-0">
                    <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</div>
                </div>
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @@click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" class="mt-1 py-1 bg-slate-800 rounded-lg">
                <div class="px-3 py-2 text-xs text-slate-400">Role: {{ ucfirst(Auth::user()->role) }}</div>
                <a href="{{ route('profile.security') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Security
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </aside>
    @endauth

    <div class="flex-1 flex flex-col min-h-screen" @auth md:ml-0 @endauth>
        @auth
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-slate-200 md:hidden">
            <div class="flex items-center justify-between px-4 h-14">
                <button @@click="sidebarOpen = true" class="text-slate-600 hover:text-slate-900 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="font-semibold text-slate-900">{{ config('app.name') }}</span>
                <a href="{{ route('notifications.index') }}" class="relative text-slate-600 hover:text-slate-900 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </div>
        </header>
        @endauth

        <main class="flex-1 p-4 md:p-6 lg:p-8">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg" role="alert">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @@click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @@click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
            @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div x-data="{ show: true }" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <strong>Please fix the following errors:</strong>
                    <button @@click="show = !show" class="ml-auto text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" :class="show ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <ul x-show="show" class="mt-2 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @vite('resources/js/app.js')
    <script>
        document.addEventListener('alpine:init', () => {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('[type="submit"]');
                    if (btn && !btn.dataset.noLoading) {
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    }
                });
            });
        });

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
