<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Two-Factor Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
        .code-input { width: 100%; text-align: center; font-size: 2rem; font-weight: 700; letter-spacing: 12px; padding: 16px; border-radius: 12px; border: 2px solid #e2e8f0; font-family: 'Courier New', monospace; }
        .code-input:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
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
                            <div class="bg-indigo-50 rounded-xl inline-flex p-3 mb-3">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-xl text-slate-800 mb-1">Two-Factor Authentication</h4>
                            <p class="text-slate-500 text-sm">Enter the 6-digit code sent to your email</p>
                        </div>

                        <form method="POST" action="{{ route('two-factor.verify') }}">
                            @csrf
                            <div class="mb-5">
                                <input type="text" class="code-input w-full text-center text-3xl font-bold tracking-widest px-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 @error('code') border-red-300 @enderror" id="code" name="code" placeholder="000000" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" autofocus required>
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3.5 rounded-xl hover:bg-indigo-700 transition-colors font-semibold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Verify Code
                            </button>
                        </form>

                        <div class="text-center mt-5">
                            <p class="text-slate-500 text-sm mb-2">Didn't receive the code?</p>
                            <a href="{{ route('two-factor.challenge') }}" class="border border-slate-300 text-slate-600 px-4 py-2 rounded-full hover:bg-slate-50 transition-colors font-medium text-sm inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Resend Code
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('two-factor.recovery') }}" class="text-indigo-600 hover:text-indigo-800 underline text-sm inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Use a recovery code
                            </a>
                        </div>

                        <hr class="my-5 border-slate-200">
                        <div class="text-center">
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-slate-700 underline text-sm inline-flex items-center gap-1 bg-transparent border-0 p-0 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Cancel and sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('code')?.addEventListener('input', function(e) { this.value = this.value.replace(/\D/g, '').slice(0, 6); });
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.disabled = true; } }); });
    </script>
</body>
</html>
