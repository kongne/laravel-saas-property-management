<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
        .password-checklist { list-style: none; padding: 0; margin: 0.5rem 0 0; font-size: 0.75rem; }
        .password-checklist li { margin-bottom: 0.25rem; color: #64748b; }
        .password-checklist li.valid { color: #059669; }
        .password-checklist li.invalid { color: #dc2626; }
    </style>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
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
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200">
                        <h4 class="font-bold text-xl text-slate-800 mb-1">Create your account</h4>
                        <p class="text-slate-500 text-sm">Start managing your properties in minutes</p>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Full Name</label>
                                    <input type="text" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name">
                                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                                    <input type="email" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Phone (optional)</label>
                                    <input type="text" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" id="password" name="password" placeholder="Create a strong password" required autocomplete="new-password">
                                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Confirm Password</label>
                                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div>
                                    <ul class="password-checklist" id="passwordChecklist">
                                        <li id="checkLength">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>At least 12 characters
                                        </li>
                                        <li id="checkUpper">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>One uppercase letter
                                        </li>
                                        <li id="checkLower">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>One lowercase letter
                                        </li>
                                        <li id="checkNumber">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>One number
                                        </li>
                                        <li id="checkSymbol">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>One special character
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">I want to register as</label>
                                    <div class="flex gap-4">
                                        <div class="flex items-start">
                                            <input type="radio" name="role" id="roleLandlord" value="landlord" class="h-4 w-4 mt-1 border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('role', 'landlord') === 'landlord' ? 'checked' : '' }}>
                                            <label class="ml-2" for="roleLandlord">
                                                <strong class="text-sm text-slate-800">Landlord</strong><br><span class="text-xs text-slate-500">Manage properties and tenants</span>
                                            </label>
                                        </div>
                                        <div class="flex items-start">
                                            <input type="radio" name="role" id="roleTenant" value="tenant" class="h-4 w-4 mt-1 border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('role') === 'tenant' ? 'checked' : '' }}>
                                            <label class="ml-2" for="roleTenant">
                                                <strong class="text-sm text-slate-800">Tenant</strong><br><span class="text-xs text-slate-500">View lease and pay rent</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <div class="flex items-start">
                                        <input type="checkbox" class="h-4 w-4 mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 @error('agree_terms') border-red-300 @enderror" id="agree_terms" name="agree_terms" value="1">
                                        <label class="ml-2 text-sm text-slate-600" for="agree_terms">I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-800 underline">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:text-indigo-800 underline">Privacy Policy</a></label>
                                    </div>
                                    @error('agree_terms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-semibold flex items-center justify-center gap-2">
                                        Create Account
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center mt-5">
                            <p class="text-slate-500 text-sm">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Sign in</a></p>
                        </div>
                    </div>
                </div>
                <p class="text-center text-slate-500 text-xs mt-5">
                    <a href="/" class="text-slate-500 no-underline hover:text-slate-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>Back to home
                    </a>
                </p>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.disabled = true; } }); });
        document.getElementById('password')?.addEventListener('input', function() {
            var v = this.value;
            function check(id, test) {
                var el = document.getElementById(id);
                if(!el) return;
                var ok = test(v);
                el.className = ok ? 'valid' : 'invalid';
                var text = el.textContent.trim().replace(/[^a-zA-Z0-9 .]/g, '').trim();
                el.innerHTML = (ok
                    ? '<svg class="w-3 h-3 inline mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    : '<svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>') + text;
            }
            check('checkLength', function(v){return v.length >= 12;});
            check('checkUpper', function(v){return /[A-Z]/.test(v);});
            check('checkLower', function(v){return /[a-z]/.test(v);});
            check('checkNumber', function(v){return /[0-9]/.test(v);});
            check('checkSymbol', function(v){return /[^a-zA-Z0-9]/.test(v);});
        });
    </script>
</body>
</html>
