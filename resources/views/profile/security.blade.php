@extends('layouts.app')

@section('title', __('Security Settings'))

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        {{ __('Security Settings') }}
    </h2>
    <p class="text-slate-500 mt-1">{{ __('Manage your account security and connected accounts') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="p-6">
                <h5 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ __('Two-Factor Authentication') }}
                </h5>
                <p class="text-slate-500 text-sm mb-4">{{ __('Add an extra layer of security by requiring a verification code in addition to your password.') }}</p>

                @if($user->hasTwoFactorEnabled())
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>{{ __('Two-factor authentication is') }} <strong>{{ __('enabled') }}</strong>.</div>
                    </div>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-4" onsubmit="return confirm('{{ __('Are you sure you want to disable two-factor authentication?') }}');">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Enter your password to disable') }}</label>
                            <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" id="password" name="password" placeholder="{{ __('Your current password') }}" required>
                        </div>
                        <button type="submit" class="px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50 inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            {{ __('Disable 2FA') }}
                        </button>
                    </form>
                @else
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <div>{{ __('Two-factor authentication is') }} <strong>{{ __('not enabled') }}</strong>.</div>
                    </div>

                    <p class="text-sm text-slate-500 mt-3 mb-4">{{ __('To enable 2FA, we\'ll send a verification code to your email. Enter it below to confirm.') }}</p>

                    <form method="POST" action="{{ route('two-factor.enable') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                        @csrf
                        <div class="sm:col-span-3">
                            <input type="text" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" id="code" name="code" placeholder="{{ __('Enter 6-digit code') }}" maxlength="6" inputmode="numeric" pattern="[0-9]*">
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm w-full inline-flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Enable 2FA') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-2">
                        <form method="POST" action="{{ route('two-factor.send-code') }}">
                            @csrf
                            <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium p-0 inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ __('Send verification code') }}
                            </button>
                        </form>
                    </div>

                    @if(session('recovery_codes'))
                    <div class="mt-4 p-4 bg-slate-800 text-white rounded-xl">
                        <h6 class="font-bold text-amber-400 mb-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            {{ __('Recovery Codes') }}
                        </h6>
                        <p class="text-sm text-white/70 mb-3">{{ __('Save these codes in a secure place. Each code can be used once to access your account if you lose your 2FA device.') }}</p>
                        <div class="bg-black/30 rounded-lg p-3 font-mono text-sm space-y-1">
                            @foreach(session('recovery_codes') as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="p-6">
                <h5 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    {{ __('Connected Accounts') }}
                </h5>
                <p class="text-slate-500 text-sm mb-4">{{ __('Link your social accounts for one-click login.') }}</p>

                @php $linkedProviders = $user->socialLogins->pluck('provider')->toArray(); @endphp

                <div class="flex items-center justify-between py-3 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <div>
                            <div class="font-medium text-slate-800">{{ __('Google') }}</div>
                            <small class="text-slate-500">
                                @if(in_array('google', $linkedProviders)) {{ __('Connected') }} @else {{ __('Not connected') }} @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('google', $linkedProviders))
                        <x-confirm action="{{ route('profile.unlink-social', 'google') }}" method="POST" message="{{ __('Unlink your Google account?') }}" confirmText="{{ __('Unlink') }}">
                            <button type="button" class="px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50">{{ __('Unlink') }}</button>
                        </x-confirm>
                    @else
                        <a href="{{ route('social.redirect', 'google') }}" class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 transition-colors">{{ __('Connect') }}</a>
                    @endif
                </div>

                <div class="flex items-center justify-between py-3 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#333" d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                        <div>
                            <div class="font-medium text-slate-800">{{ __('GitHub') }}</div>
                            <small class="text-slate-500">
                                @if(in_array('github', $linkedProviders)) {{ __('Connected') }} @else {{ __('Not connected') }} @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('github', $linkedProviders))
                        <x-confirm action="{{ route('profile.unlink-social', 'github') }}" method="POST" message="{{ __('Unlink your GitHub account?') }}" confirmText="{{ __('Unlink') }}">
                            <button type="button" class="px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50">{{ __('Unlink') }}</button>
                        </x-confirm>
                    @else
                        <a href="{{ route('social.redirect', 'github') }}" class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 transition-colors">{{ __('Connect') }}</a>
                    @endif
                </div>

                <div class="flex items-center justify-between py-3 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <div>
                            <div class="font-medium text-slate-800">{{ __('Facebook') }}</div>
                            <small class="text-slate-500">
                                @if(in_array('facebook', $linkedProviders)) {{ __('Connected') }} @else {{ __('Not connected') }} @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('facebook', $linkedProviders))
                        <x-confirm action="{{ route('profile.unlink-social', 'facebook') }}" method="POST" message="{{ __('Unlink your Facebook account?') }}" confirmText="{{ __('Unlink') }}">
                            <button type="button" class="px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50">{{ __('Unlink') }}</button>
                        </x-confirm>
                    @else
                        <a href="{{ route('social.redirect', 'facebook') }}" class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 transition-colors">{{ __('Connect') }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="p-6">
                <h5 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('Email Address') }}
                </h5>
                <p class="text-slate-500 text-sm mb-4">{{ __('Current email') }}: <strong>{{ $user->email }}</strong></p>
                <form method="POST" action="{{ route('profile.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Current Password') }}</label>
                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('current_password') border-red-500 @enderror" name="current_password" required>
                        @error('current_password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('New Email Address') }}</label>
                        <input type="email" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('new_email') border-red-500 @enderror" name="new_email" placeholder="new@example.com" required>
                        @error('new_email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Send Verification') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="p-6">
                <h5 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    {{ __('Change Password') }}
                </h5>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Current Password') }}</label>
                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('current_password') border-red-500 @enderror" name="current_password" required>
                        @error('current_password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('New Password') }}</label>
                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('password') border-red-500 @enderror" name="password" required>
                        @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Confirm New Password') }}</label>
                        <input type="password" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Update Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
