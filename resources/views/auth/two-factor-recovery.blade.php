<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Recovery Code</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
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
                <div class="bg-white rounded-xl shadow-sm border border-slate-200" style="max-width: 440px; margin: 0 auto;">
                    <div class="p-8">
                        <div class="text-center mb-5">
                            <div class="bg-amber-50 rounded-xl inline-flex p-3 mb-3">
                                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-xl text-slate-800 mb-1">Recovery Code</h4>
                            <p class="text-slate-500 text-sm">Use a recovery code to access your account</p>
                        </div>

                        <form method="POST" action="{{ route('two-factor.recovery.verify') }}">
                            @csrf
                            <div class="mb-5">
                                <label for="recovery_code" class="block text-sm font-medium text-slate-700 mb-1.5">Recovery Code</label>
                                <input type="text" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('recovery_code') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="recovery_code" name="recovery_code" placeholder="XXXX-XXXX-XXXX-XXXX" required autofocus>
                                @error('recovery_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-amber-500 text-white px-4 py-3.5 rounded-xl hover:bg-amber-600 transition-colors font-semibold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                Verify Recovery Code
                            </button>
                        </form>

                        <hr class="my-5 border-slate-200">
                        <div class="text-center">
                            <a href="{{ route('two-factor.challenge') }}" class="text-indigo-600 hover:text-indigo-800 underline text-sm inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                Back to 2FA code
                            </a>
                            <span class="text-slate-400 mx-2">|</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-slate-700 underline text-sm inline-flex items-center gap-1 bg-transparent border-0 p-0 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.disabled = true; } }); });
    </script>
</body>
</html>
