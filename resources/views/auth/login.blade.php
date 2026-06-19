<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('dark') === 'true' || (!localStorage.getItem('dark') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="dark ? 'dark' : ''" x-init="localStorage.setItem('dark', dark); $watch('dark', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); min-height: 100vh; display: flex; align-items: center; }
        .dark body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    </style>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="text-center mb-4">
                    <a href="/" class="no-underline">
                        <div class="inline-flex items-center gap-2">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl inline-flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">{{ config('app.name') }}</span>
                        </div>
                    </a>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <h4 class="font-bold text-xl text-slate-800 dark:text-slate-100 mb-1">Welcome back</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Sign in to your account to continue</p>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Email Address</label>
                                <input type="email" class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="email">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between items-center">
                                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Password</label>
                                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 underline">Forgot?</a>
                                </div>
                                <input type="password" class="w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4 flex items-center">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800" id="remember" name="remember">
                                <label class="ml-2 text-sm text-slate-600 dark:text-slate-400" for="remember">Remember me for 30 days</label>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                                Sign In
                            </button>
                        </form>

                        <div class="relative my-5">
                            <hr class="border-slate-200 dark:border-slate-700">
                            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-800 px-3 text-slate-500 dark:text-slate-400 text-xs">or continue with</span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <a href="{{ route('social.redirect', 'google') }}" class="border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium flex items-center justify-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                Google
                            </a>
                            <a href="{{ route('social.redirect', 'github') }}" class="border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium flex items-center justify-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#333" d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                                GitHub
                            </a>
                            <a href="{{ route('social.redirect', 'facebook') }}" class="border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium flex items-center justify-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                        </div>

                        <div class="text-center mt-5">
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Don't have an account? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">Create one</a></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-3 mt-5">
                    <p class="text-center text-slate-500 dark:text-slate-400 text-xs">
                        <a href="/" class="text-slate-500 dark:text-slate-400 no-underline hover:text-slate-700 dark:hover:text-slate-300">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>Back to home
                        </a>
                    </p>
                    <button @click="dark = !dark" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 flex items-center gap-1">
                        <svg x-show="!dark" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span x-text="dark ? 'Light' : 'Dark'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.disabled = true; } }); });
    </script>
</body>
</html>
