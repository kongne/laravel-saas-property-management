<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <title>{{ config('app.name') }} - Smart Property Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
        .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .pricing-card { border-radius: 20px; transition: transform 0.3s ease; border: 1px solid #e2e8f0; }
        .pricing-card:hover { transform: translateY(-8px); }
        .pricing-card.featured { border: 2px solid #6366f1; transform: scale(1.02); }
        .btn-cta { padding: 14px 40px; border-radius: 50px; font-weight: 600; }
        .section-padding { padding: 100px 0; }
        @media (max-width: 768px) { .section-padding { padding: 60px 0; } }
        .navbar-blur { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95) !important; border-bottom: 1px solid rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav class="fixed top-0 left-0 right-0 z-50 navbar-blur">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a class="font-bold text-xl no-underline text-slate-900 flex items-center gap-2" href="/">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ config('app.name') }}
                </a>
                <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100" type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="hidden lg:flex items-center gap-2" id="mobileMenu">
                    <a class="px-3 py-2 text-sm text-slate-600 hover:text-slate-900 no-underline rounded-lg hover:bg-slate-50" href="#features">Features</a>
                    <a class="px-3 py-2 text-sm text-slate-600 hover:text-slate-900 no-underline rounded-lg hover:bg-slate-50" href="#pricing">Pricing</a>
                    <a class="px-3 py-2 text-sm text-slate-600 hover:text-slate-900 no-underline rounded-lg hover:bg-slate-50" href="#testimonials">Testimonials</a>
                    <a class="px-3 py-2 text-sm text-slate-600 hover:text-slate-900 no-underline rounded-lg hover:bg-slate-50" href="#contact">Contact</a>
                    @guest
                        <a class="border border-slate-300 text-slate-600 px-4 py-1.5 rounded-lg hover:bg-slate-50 transition-colors font-medium text-sm ml-3 no-underline" href="{{ route('login') }}">Sign In</a>
                        <a class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm no-underline" href="{{ route('register') }}">Get Started</a>
                    @else
                        <a class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm ml-3 no-underline" href="{{ route('dashboard') }}">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-gradient text-white position-relative overflow-hidden pt-20" style="min-height: 100vh;">
        <div class="max-w-7xl mx-auto px-4 position-relative" style="z-index: 2;">
            <div class="flex items-center" style="min-height: calc(100vh - 80px);">
                <div class="w-full lg:w-7/12">
                    <span class="inline-flex items-center gap-1.5 bg-indigo-900/50 text-indigo-200 px-3 py-2 mb-4 rounded-full text-sm font-normal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Trusted by 500+ Property Managers
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-5 leading-tight">Smart Property<br>Management <span class="text-indigo-400">Simplified</span></h1>
                    <p class="text-lg md:text-xl mb-6 text-white/75 lg:w-10/12">Track properties, manage tenants, collect payments, and handle maintenance requests — all from one powerful, secure platform.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-3.5 rounded-full hover:bg-indigo-700 transition-colors font-semibold inline-flex items-center gap-2 text-lg">
                            Start Free Trial
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#features" class="border border-white/30 text-white px-8 py-3.5 rounded-full hover:bg-white/10 transition-colors font-semibold text-lg no-underline">
                            Explore Features
                        </a>
                    </div>
                    <div class="flex gap-6 mt-8 pt-4">
                        <div>
                            <small class="text-white/75 text-xs">Trusted by</small>
                            <div class="flex gap-3 mt-1">
                                <span class="font-bold text-xl">500+</span>
                                <span class="text-white/50">|</span>
                                <span class="font-bold text-xl">10K+</span>
                            </div>
                            <div class="flex gap-3">
                                <small class="text-white/75 text-xs">Managers</small>
                                <small class="text-white/75 text-xs">Properties</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block lg:w-5/12 text-center">
                    <div class="relative">
                        <div class="bg-indigo-900/20 rounded-2xl p-8 shadow-lg">
                            <svg class="w-8 h-8 inline text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <svg class="w-8 h-8 inline ml-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <svg class="w-8 h-8 inline ml-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <svg class="w-8 h-8 inline ml-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="mt-5">
                                <div class="inline-block w-8 h-8 border-2 border-indigo-400 border-t-transparent rounded-full animate-spin" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0" style="height: 150px; background: linear-gradient(transparent, #fff);"></div>
    </section>

    <section id="features" class="section-padding bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 px-3 py-2 mb-3 rounded-full text-sm font-medium">Everything You Need</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800">Powerful Features for Modern Management</h2>
                <p class="text-slate-500 text-lg mt-2">All the tools you need to manage your properties efficiently</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="bg-indigo-50 rounded-xl inline-flex p-3 mb-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Property Management</h5>
                        <p class="text-slate-500 text-sm">Manage multiple properties with detailed info, images, and unit tracking. Organize by status, location, and type.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="bg-emerald-50 rounded-xl inline-flex p-3 mb-3">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Tenant Portal</h5>
                        <p class="text-slate-500 text-sm">Tenants can view their lease, make payments, submit maintenance requests, and track their history.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="bg-amber-50 rounded-xl inline-flex p-3 mb-3">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Payment Tracking</h5>
                        <p class="text-slate-500 text-sm">Track rent payments, late fees, and generate receipts. Get notified of overdue and pending payments.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="bg-red-50 rounded-xl inline-flex p-3 mb-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Maintenance Tracking</h5>
                        <p class="text-slate-500 text-sm">Submit, assign, and track maintenance requests with priority levels, images, and resolution tracking.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="bg-cyan-50 rounded-xl inline-flex p-3 mb-3">
                            <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Lease Management</h5>
                        <p class="text-slate-500 text-sm">Create and manage leases with automatic rent reminders, renewals, and termination workflows.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 feature-card p-4 h-100">
                    <div class="p-4 text-center">
                        <div class="rounded-xl inline-flex p-3 mb-3" style="background: rgba(99,102,241,0.1);">
                            <svg class="w-8 h-8" style="color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h5 class="text-lg font-semibold text-slate-800">Role-Based Access</h5>
                        <p class="text-slate-500 text-sm">Granular permissions for admins, landlords, and tenants. Each role sees exactly what they need.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-slate-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2">
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-2 mb-3 rounded-full text-sm font-medium">Real-time Dashboard</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-800">See Your Business at a Glance</h2>
                    <p class="text-slate-500 text-lg mt-3">Get instant insights into your property portfolio with role-specific dashboards showing key metrics.</p>
                    <ul class="mt-5 space-y-3">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-slate-700">Real-time revenue tracking and forecasting</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-slate-700">Occupancy rates and vacancy alerts</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-slate-700">Overdue payment notifications</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-slate-700">Maintenance request summaries</span>
                        </li>
                    </ul>
                </div>
                <div class="lg:w-1/2 mt-8 lg:mt-0 lg:pl-12">
                    <div class="bg-white rounded-2xl shadow-lg border-0 overflow-hidden">
                        <div class="p-8" style="background: linear-gradient(135deg, #1a1a2e, #0f3460); color: white;">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-4 rounded-xl" style="background: rgba(255,255,255,0.1);">
                                    <small class="text-white/75">Revenue</small>
                                    <h3 class="text-2xl font-bold mt-1">$12,450</h3>
                                </div>
                                <div class="p-4 rounded-xl" style="background: rgba(255,255,255,0.1);">
                                    <small class="text-white/75">Properties</small>
                                    <h3 class="text-2xl font-bold mt-1">24</h3>
                                </div>
                                <div class="p-4 rounded-xl" style="background: rgba(255,255,255,0.1);">
                                    <small class="text-white/75">Occupancy</small>
                                    <h3 class="text-2xl font-bold mt-1">94%</h3>
                                </div>
                                <div class="p-4 rounded-xl" style="background: rgba(255,255,255,0.1);">
                                    <small class="text-white/75">Pending</small>
                                    <h3 class="text-2xl font-bold mt-1 text-amber-400">3</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="section-padding bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 px-3 py-2 mb-3 rounded-full text-sm font-medium">Simple Pricing</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800">Plans That Scale With You</h2>
                <p class="text-slate-500 text-lg mt-2">Start free, upgrade when you grow</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 pricing-card p-4 w-full max-w-sm">
                    <div class="p-4">
                        <h5 class="text-lg font-semibold text-slate-800">Starter</h5>
                        <h2 class="font-bold text-3xl mb-1 text-slate-800">$19<small class="text-base font-normal text-slate-500">/mo</small></h2>
                        <p class="text-slate-500 text-sm">For individual landlords</p>
                        <hr class="my-4 border-slate-200">
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Up to 10 properties
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Basic tenant management
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Payment tracking
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Email support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-5 w-full border border-slate-300 text-slate-600 px-4 py-3 rounded-lg hover:bg-slate-50 transition-colors font-medium block text-center no-underline">Start Free Trial</a>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm pricing-card featured p-4 w-full max-w-sm">
                    <div class="p-4">
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-600 text-white mb-3">Most Popular</span>
                        <h5 class="text-lg font-semibold text-slate-800">Professional</h5>
                        <h2 class="font-bold text-3xl mb-1 text-slate-800">$49<small class="text-base font-normal text-slate-500">/mo</small></h2>
                        <p class="text-slate-500 text-sm">For professional managers</p>
                        <hr class="my-4 border-slate-200">
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Up to 50 properties
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Advanced tenant portal
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Automated rent reminders
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Maintenance workflows
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Priority support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-5 w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium block text-center no-underline">Start Free Trial</a>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 pricing-card p-4 w-full max-w-sm">
                    <div class="p-4">
                        <h5 class="text-lg font-semibold text-slate-800">Enterprise</h5>
                        <h2 class="font-bold text-3xl mb-1 text-slate-800">$99<small class="text-base font-normal text-slate-500">/mo</small></h2>
                        <p class="text-slate-500 text-sm">For large agencies</p>
                        <hr class="my-4 border-slate-200">
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Unlimited properties
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                API access
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Custom branding
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Advanced reporting
                            </li>
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Dedicated account manager
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-5 w-full border border-slate-300 text-slate-600 px-4 py-3 rounded-lg hover:bg-slate-50 transition-colors font-medium block text-center no-underline">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="section-padding bg-slate-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 px-3 py-2 mb-3 rounded-full text-sm font-medium">Testimonials</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800">Trusted by Property Managers</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-100 rounded-2xl shadow-sm p-4 h-100">
                    <div class="p-4">
                        <div class="mb-3 text-amber-400 text-lg font-bold">★★★★★</div>
                        <p class="mb-3 text-slate-600 text-sm">"This platform has completely transformed how I manage my 15 properties. The tenant portal alone saved me hours of communication."</p>
                        <div class="flex items-center">
                            <div class="bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm" style="width:40px;height:40px;">SM</div>
                            <div class="ml-3">
                                <small class="font-semibold block text-slate-800">Sarah Mitchell</small>
                                <small class="text-slate-500 text-xs">Property Manager, 15+ properties</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-100 rounded-2xl shadow-sm p-4 h-100">
                    <div class="p-4">
                        <div class="mb-3 text-amber-400 text-lg font-bold">★★★★★</div>
                        <p class="mb-3 text-slate-600 text-sm">"The payment tracking and automated reminders reduced my late payments by 80%. Absolutely essential tool for any landlord."</p>
                        <div class="flex items-center">
                            <div class="bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm" style="width:40px;height:40px;">JD</div>
                            <div class="ml-3">
                                <small class="font-semibold block text-slate-800">James Donnelly</small>
                                <small class="text-slate-500 text-xs">Landlord, 8 properties</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-100 rounded-2xl shadow-sm p-4 h-100">
                    <div class="p-4">
                        <div class="mb-3 text-amber-400 text-lg font-bold">★★★★★</div>
                        <p class="mb-3 text-slate-600 text-sm">"As a tenant, being able to submit maintenance requests and track payments online is incredibly convenient. Great platform!"</p>
                        <div class="flex items-center">
                            <div class="bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-sm" style="width:40px;height:40px;">AK</div>
                            <div class="ml-3">
                                <small class="font-semibold block text-slate-800">Alex Kim</small>
                                <small class="text-slate-500 text-xs">Tenant</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section-padding" style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-center">
                <div class="lg:w-2/3 text-center text-white">
                    <h2 class="text-4xl md:text-5xl font-bold mb-5">Ready to Simplify Your Property Management?</h2>
                    <p class="text-lg mb-6 opacity-75">Join thousands of property managers who trust our platform. Start your free trial today.</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('register') }}" class="bg-white text-slate-900 px-8 py-3.5 rounded-full hover:bg-slate-100 transition-colors font-semibold inline-flex items-center gap-2 text-lg no-underline">
                            Get Started Free
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="mailto:support@propertymanager.com" class="border border-white/30 text-white px-8 py-3.5 rounded-full hover:bg-white/10 transition-colors font-semibold text-lg no-underline">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                <div class="lg:col-span-2">
                    <h5 class="text-lg font-semibold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        {{ config('app.name') }}
                    </h5>
                    <p class="text-slate-400 text-sm">Your all-in-one property management solution. Secure, reliable, and easy to use.</p>
                    <div class="flex gap-3 mt-3">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Product</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="text-slate-400 hover:text-white no-underline">Features</a></li>
                        <li><a href="#pricing" class="text-slate-400 hover:text-white no-underline">Pricing</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-white no-underline">Sign Up</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Company</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">About</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white no-underline">Blog</a></li>
                        <li><a href="#contact" class="text-slate-400 hover:text-white no-underline">Contact</a></li>
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
            <div class="text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Built with
                <svg class="w-4 h-4 inline text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                security first.
            </div>
        </div>
    </footer>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" class="fixed bottom-0 right-0 p-3" style="z-index: 9999;">
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <span>{{ session('success') }}</span>
            <button @@click="show = false" class="text-emerald-500 hover:text-emerald-700 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif
    <script>if('serviceWorker'in navigator){window.addEventListener('load',function(){navigator.serviceWorker.register('/sw.js').catch(function(){})});}</script>
</body>
</html>
