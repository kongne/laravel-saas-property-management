@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Users</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-4">
            <div class="md:col-span-4">
                <input type="text" name="search" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-2">
                <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="landlord" {{ request('role') === 'landlord' ? 'selected' : '' }}>Landlord</option>
                    <option value="tenant" {{ request('role') === 'tenant' ? 'selected' : '' }}>Tenant</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">Filter</button>
            </div>
            <div class="md:col-span-2">
                <a href="{{ route('users.index') }}" class="w-full block text-center px-2.5 py-1.5 text-xs font-medium text-slate-600 border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Last Login</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $user->name }}</td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{
                                $user->role === 'admin' ? 'bg-red-100 text-red-700' :
                                ($user->role === 'landlord' ? 'bg-indigo-100 text-indigo-700' :
                                'bg-slate-100 text-slate-700')
                            }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            @if($user->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Active</span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-t border-slate-100 text-slate-600">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                        <td class="px-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('users.edit', $user) }}" class="px-2.5 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-300 rounded-md hover:bg-indigo-50 transition-colors">Edit</a>
                                <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 text-xs font-medium {{
                                        $user->is_active ? 'text-amber-600 border border-amber-300 hover:bg-amber-50' : 'text-emerald-600 border border-emerald-300 hover:bg-emerald-50'
                                    }} rounded-md transition-colors">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50 transition-colors">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-slate-500 border-t border-slate-100">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
