<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: JSON.parse(localStorage.getItem('dark') || 'false') }" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#6366f1">
    <title>{{ config('app.name') }} - Features</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased">

    <nav class="fixed top-0 left-0 right-0 z-50 navbar-blur">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a class="font-bold text-xl no-underline text-slate-900 dark:text-white flex items-center gap-2" href="/">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ config('app.name') }}
                </a>
                <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden lg:flex items-center gap-2" id="mobileMenu">
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('features') }}">Features</a>
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('pricing') }}">Pricing</a>
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('contact') }}">Contact</a>
                    @guest
                        <a class="border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium text-sm ml-3 no-underline" href="{{ route('login') }}">Sign In</a>
                        <a class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm no-underline" href="{{ route('register') }}">Get Started</a>
                    @else
                        <a class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm ml-3 no-underline" href="{{ route('dashboard') }}">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <section class="landing-section pt-32">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10" x-data="scrollReveal">
                <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 px-3 py-2 mb-3 rounded-full text-sm font-medium">Everything You Need</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white">All Features, One Platform</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg mt-2 max-w-2xl mx-auto">Every tool thoughtfully designed to make property management effortless.</p>
                </div>
            </div>

            <div class="space-y-16">
                @php
                    $featureGroups = [
                        ['title' => 'Property & Unit Management', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'items' => [
                            'Add and manage unlimited properties with rich details',
                            'Organize units within each property with rent tracking',
                            'Upload property images and documents',
                            'Filter and search across your entire portfolio',
                            'Track occupancy status in real time',
                        ]],
                        ['title' => 'Tenant Portal & Leases', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'items' => [
                            'Dedicated portal for tenants to view lease details',
                            'Create, renew, and terminate leases seamlessly',
                            'Automatic rent amount and date tracking',
                            'Lease expiry alerts and renewal reminders',
                            'Tenant communication history',
                        ]],
                        ['title' => 'Payments & Financials', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'items' => [
                            'Track rent payments, late fees, and deposits',
                            'Mark payments as paid with partial amount support',
                            'Auto-generate invoice numbers',
                            'Export payment reports as CSV',
                            'Download PDF receipts for tenant records',
                        ]],
                        ['title' => 'Maintenance Management', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'items' => [
                            'Tenants can submit requests with priority levels',
                            'Admin dashboard to assign and track progress',
                            'Resolve and document completed work',
                            'Categorized by urgency and type',
                            'Full audit trail of all maintenance activity',
                        ]],
                        ['title' => 'Security & Access Control', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'items' => [
                            'Role-based access: Admin, Landlord, Tenant',
                            'Two-factor authentication (2FA) support',
                            'Social login via Google, GitHub, Facebook',
                            'Activity audit logs for all user actions',
                            'User account activation/deactivation',
                        ]],
                        ['title' => 'Reporting & Analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'items' => [
                            'Interactive dashboard with Chart.js graphs',
                            'Revenue trends and payment status breakdown',
                            'Export data to CSV for offline analysis',
                            'Global search across all entities',
                            'Role-specific dashboard views',
                        ]],
                    ];
                @endphp

                @foreach($featureGroups as $group)
                <div class="flex flex-col lg:flex-row items-start gap-8" x-data="scrollReveal">
                    <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                        <div class="lg:w-72 flex-shrink-0">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $group['icon'] }}"/>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $group['title'] }}</h2>
                            </div>
                        </div>
                        <ul class="space-y-3 mt-4 lg:mt-0">
                            @foreach($group['items'] as $item)
                            <li class="flex items-start gap-2 text-slate-600 dark:text-slate-300">
                                <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-16 py-12" x-data="scrollReveal">
                <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-4">Ready to Get Started?</h2>
                    <p class="text-slate-500 dark:text-slate-400 mb-6">Start your 14-day free trial. No credit card required.</p>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-3.5 rounded-full hover:bg-indigo-700 transition-colors font-semibold inline-flex items-center gap-2 no-underline">
                        Start Free Trial
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                <div class="lg:col-span-2">
                    <h5 class="text-lg font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ config('app.name') }}
                    </h5>
                    <p class="text-slate-400 text-sm">Your all-in-one property management solution.</p>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Product</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('features') }}" class="text-slate-400 hover:text-white no-underline">Features</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-slate-400 hover:text-white no-underline">Pricing</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-white no-underline">Sign Up</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Company</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">About</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-400 hover:text-white no-underline">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Legal</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Privacy</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Terms</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Support</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Documentation</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">FAQ</a></li>
                        <li><a href="mailto:support@propertymanager.com" class="text-slate-400 hover:text-white no-underline">Email</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-5 border-slate-700 opacity-50">
            <div class="text-center text-xs text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
        </div>
    </footer>
</body>
</html>
