<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: JSON.parse(localStorage.getItem('dark') || 'false') }" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#6366f1">
    <title>{{ config('app.name') }} - Contact</title>
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
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 px-3 py-2 mb-3 rounded-full text-sm font-medium">Get in Touch</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white">We'd Love to Hear From You</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg mt-2">Have questions? Our team is here to help.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-4xl mx-auto">
                <div class="space-y-6" x-data="scrollReveal">
                    <div class="animate-fade-in-up" :class="visible && 'is-visible'">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800 dark:text-white">Email</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">support@propertymanager.com</p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">We respond within 24 hours</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 mt-6">
                            <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800 dark:text-white">Live Chat</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Available Mon-Fri, 9AM-6PM EST</p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Instant answers from our team</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 mt-6">
                            <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800 dark:text-white">Knowledge Base</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Browse documentation and FAQs</p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Self-serve answers anytime</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-6" x-data="scrollReveal">
                    <div class="animate-fade-in-up delay-100" :class="visible && 'is-visible'">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Send Us a Message</h3>
                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-forms.label for="name">Name</x-forms.label>
                                <x-forms.input name="name" required />
                            </div>
                            <div>
                                <x-forms.label for="email">Email</x-forms.label>
                                <x-forms.input type="email" name="email" required />
                            </div>
                            <div>
                                <x-forms.label for="message">Message</x-forms.label>
                                <x-forms.textarea name="message" rows="4" required />
                            </div>
                            <x-forms.button type="submit" variant="primary" class="w-full">Send Message</x-forms.button>
                        </form>
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

    @if(session('success'))
    <x-toast type="success" :message="session('success')" />
    @endif
</body>
</html>
