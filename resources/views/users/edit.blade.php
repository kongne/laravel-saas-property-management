@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Edit User: {{ $user->name }}</h2>
    <a href="{{ route('users.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-colors font-medium text-sm border border-slate-300">Back to Users</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('name') border-red-500 @enderror" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('email') border-red-500 @enderror" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('phone') border-red-500 @enderror" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                    <select name="role" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('role') border-red-500 @enderror">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="landlord" {{ old('role', $user->role) === 'landlord' ? 'selected' : '' }}>Landlord</option>
                        <option value="tenant" {{ old('role', $user->role) === 'tenant' ? 'selected' : '' }}>Tenant</option>
                    </select>
                    @error('role') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="mt-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" id="isActive" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-700">Account Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 mt-6">
    <div class="px-6 py-4 border-b border-slate-200">
        <h5 class="text-lg font-semibold text-slate-800">Account Info</h5>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div><strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</div>
            <div><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</div>
            <div><strong>2FA:</strong> {{ $user->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}</div>
            <div><strong>Password Changed:</strong> {{ $user->password_changed_at ? $user->password_changed_at->format('M d, Y') : 'Never' }}</div>
        </div>
    </div>
</div>
@endsection
