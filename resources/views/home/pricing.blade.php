<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: JSON.parse(localStorage.getItem('dark') || 'false') }" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#6366f1">
    <title>{{ config('app.name') }} - Pricing</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased">

    <nav class="fixed top-0 left-0 right-0 z-50 navbar-blur">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a class="font-bold text-xl no-underline text-slate-900 dark:text-white flex items-center gap-2" href="/">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ config('app.name') }}
                </a>
                <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="hidden lg:flex items-center gap-2" id="mobileMenu">
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('home') }}#features">Features</a>
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('pricing') }}">Pricing</a>
                    <a class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white no-underline rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('home') }}#testimonials">Testimonials</a>
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
            <div class="text-center mb-6" x-data="scrollReveal">
                <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 px-3 py-2 mb-3 rounded-full text-sm font-medium">Simple Pricing</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white">Plans That Scale With You</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg mt-2">Start free, upgrade when you grow. No hidden fees.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-8">
                <div class="pricing-card bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 p-4" x-data="scrollReveal">
                    <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                        <div class="p-4">
                            <h5 class="text-lg font-semibold text-slate-800 dark:text-white">Starter</h5>
                            <h2 class="font-bold text-3xl mb-1 text-slate-800 dark:text-white">$19<small class="text-base font-normal text-slate-500">/mo</small></h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">For individual landlords</p>
                            <hr class="my-4 border-slate-200 dark:border-slate-700">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Up to 10 properties
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Basic tenant management
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Payment tracking
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Email support
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="mt-5 w-full border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium block text-center no-underline">Start Free Trial</a>
                        </div>
                    </div>
                </div>

                <div class="pricing-card featured p-4" x-data="scrollReveal">
                    <div class="animate-fade-in-up delay-100" :class="visible && 'is-visible'">
                        <div class="p-4">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-600 text-white mb-3">Most Popular</span>
                            <h5 class="text-lg font-semibold text-slate-800 dark:text-white">Professional</h5>
                            <h2 class="font-bold text-3xl mb-1 text-slate-800 dark:text-white">$49<small class="text-base font-normal text-slate-500">/mo</small></h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">For professional managers</p>
                            <hr class="my-4 border-slate-200 dark:border-slate-700">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Up to 50 properties
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Advanced tenant portal
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Automated rent reminders
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Maintenance workflows
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Priority support
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="mt-5 w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium block text-center no-underline">Start Free Trial</a>
                        </div>
                    </div>
                </div>

                <div class="pricing-card bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 p-4" x-data="scrollReveal">
                    <div class="animate-fade-in-up delay-200" :class="visible && 'is-visible'">
                        <div class="p-4">
                            <h5 class="text-lg font-semibold text-slate-800 dark:text-white">Enterprise</h5>
                            <h2 class="font-bold text-3xl mb-1 text-slate-800 dark:text-white">$99<small class="text-base font-normal text-slate-500">/mo</small></h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">For large agencies</p>
                            <hr class="my-4 border-slate-200 dark:border-slate-700">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Unlimited properties
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    API access
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Custom branding
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Advanced reporting
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Dedicated account manager
                                </li>
                            </ul>
                            <a href="{{ route('contact') }}" class="mt-5 w-full border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium block text-center no-underline">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-10" x-data="scrollReveal">
                <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                    <p class="text-slate-500 dark:text-slate-400">All plans include a 14-day free trial. No credit card required.</p>
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline mt-2 inline-block">Get started for free →</a>
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
                        <li><a href="{{ route('home') }}#features" class="text-slate-400 hover:text-white no-underline">Features</a></li>
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
