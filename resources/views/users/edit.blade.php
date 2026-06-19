@extends('layouts.app')

@section('title', __('Edit User'))

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('Edit User') }}: {{ $user->name }}</h2>
    <a href="{{ route('users.index') }}" class="btn-secondary btn-sm">Back to Users</a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">User Information</h3>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input name="name" :label="__('Name')" :value="old('name', $user->name)" />
                <x-forms.input name="email" :label="__('Email')" type="email" :value="old('email', $user->email)" />
                <x-forms.input name="phone" :label="__('Phone')" :value="old('phone', $user->phone)" />
                <div>
                    <x-forms.select name="role" :label="__('Role')" :options="['admin' => __('Admin'), 'landlord' => __('Landlord'), 'tenant' => __('Tenant')]" :value="old('role', $user->role)" />
                </div>
                <div>
                    <div class="mt-6">
                        <x-forms.checkbox name="is_active" :checked="old('is_active', $user->is_active)">Account Active</x-forms.checkbox>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <x-forms.button variant="primary">Save Changes</x-forms.button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header">
        <h5 class="text-sm font-semibold text-slate-800 dark:text-slate-200">Account Info</h5>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Created') }}</dt>
                <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->created_at->format('M d, Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Last Login') }}</dt>
                <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : __('Never') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('2FA Status') }}</dt>
                <dd class="mt-1">
                    @if($user->hasTwoFactorEnabled())
                    <span class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ __('Enabled') }}
                    </span>
                    @else
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Disabled') }}</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Password Changed') }}</dt>
                <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->password_changed_at ? $user->password_changed_at->format('M d, Y') : __('Never') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Email Verified') }}</dt>
                <dd class="mt-1">
                    @if($user->email_verified_at)
                    <span class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $user->email_verified_at->format('M d, Y') }}
                    </span>
                    @else
                    <span class="text-sm text-amber-600 dark:text-amber-400">{{ __('Not Verified') }}</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Last Login IP') }}</dt>
                <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200 font-mono">{{ $user->last_login_ip ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Account Status') }}</dt>
                <dd class="mt-1">
                    @if($user->is_active)
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700 dark:text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ __('Active') }}
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-700 dark:text-red-400">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        {{ __('Inactive') }}
                    </span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Role') }}</dt>
                <dd class="mt-1"><span class="badge whitespace-nowrap inline-flex items-center gap-1.5 {{
                    $user->role === 'admin' ? 'badge-danger' :
                    ($user->role === 'landlord' ? 'badge-info' :
                    'badge-neutral')
                }}">{{ ucfirst($user->role) }}</span></dd>
            </div>
        </dl>
    </div>
</div>
@endsection