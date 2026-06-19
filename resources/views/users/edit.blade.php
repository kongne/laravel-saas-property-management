@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit User: {{ $user->name }}</h2>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="landlord" {{ old('role', $user->role) === 'landlord' ? 'selected' : '' }}>Landlord</option>
                        <option value="tenant" {{ old('role', $user->role) === 'tenant' ? 'selected' : '' }}>Tenant</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-4">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Account Active</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h5 class="mb-0">Account Info</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</div>
            <div class="col-md-3"><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</div>
            <div class="col-md-3"><strong>2FA:</strong> {{ $user->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}</div>
            <div class="col-md-3"><strong>Password Changed:</strong> {{ $user->password_changed_at ? $user->password_changed_at->format('M d, Y') : 'Never' }}</div>
        </div>
    </div>
</div>
@endsection
