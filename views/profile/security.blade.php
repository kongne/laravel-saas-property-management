@extends('layouts.app')

@section('title', 'Security Settings')

@section('content')
<div class="page-header">
    <h2 class="fw-bold mb-1"><i class="bi bi-shield-check me-2"></i>Security Settings</h2>
    <p class="text-muted mb-0">Manage your account security and connected accounts</p>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Two-Factor Authentication</h5>
                <p class="text-muted small">Add an extra layer of security by requiring a verification code in addition to your password.</p>

                @if($user->hasTwoFactorEnabled())
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>Two-factor authentication is <strong>enabled</strong>.</div>
                    </div>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-3" onsubmit="return confirm('Are you sure you want to disable two-factor authentication?');">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-medium">Enter your password to disable</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Your current password" required>
                        </div>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-shield-slash me-1"></i>Disable 2FA
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <div>Two-factor authentication is <strong>not enabled</strong>.</div>
                    </div>

                    <p class="small text-muted mt-2 mb-3">To enable 2FA, we'll send a verification code to your email. Enter it below to confirm.</p>

                    <form method="POST" action="{{ route('two-factor.enable') }}" class="row g-2">
                        @csrf
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter 6-digit code" maxlength="6" inputmode="numeric" pattern="[0-9]*">
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-check me-1"></i>Enable 2FA
                            </button>
                        </div>
                    </form>

                    <div class="mt-2">
                        <form method="POST" action="{{ route('two-factor.send-code') }}">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">
                                <i class="bi bi-envelope me-1"></i>Send verification code
                            </button>
                        </form>
                    </div>

                    @if(session('recovery_codes'))
                    <div class="mt-4 p-3 bg-dark text-light rounded-3">
                        <h6 class="fw-bold text-warning mb-2"><i class="bi bi-key me-1"></i>Recovery Codes</h6>
                        <p class="small text-light opacity-75">Save these codes in a secure place. Each code can be used once to access your account if you lose your 2FA device.</p>
                        <div class="bg-black bg-opacity-50 rounded-2 p-3">
                            @foreach(session('recovery_codes') as $code)
                                <div class="font-monospace small">{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-link-45deg me-2 text-primary"></i>Connected Accounts</h5>
                <p class="text-muted small">Link your social accounts for one-click login.</p>

                @php $linkedProviders = $user->socialLogins->pluck('provider')->toArray(); @endphp

                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <div>
                            <div class="fw-medium">Google</div>
                            <small class="text-muted">
                                @if(in_array('google', $linkedProviders)) Connected @else Not connected @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('google', $linkedProviders))
                        <form action="{{ route('profile.unlink-social', 'google') }}" method="POST" onsubmit="return confirm('Unlink your Google account?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-primary btn-sm">Connect</a>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#333" d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                        <div>
                            <div class="fw-medium">GitHub</div>
                            <small class="text-muted">
                                @if(in_array('github', $linkedProviders)) Connected @else Not connected @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('github', $linkedProviders))
                        <form action="{{ route('profile.unlink-social', 'github') }}" method="POST" onsubmit="return confirm('Unlink your GitHub account?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect', 'github') }}" class="btn btn-outline-primary btn-sm">Connect</a>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.234 2.686.234v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <div>
                            <div class="fw-medium">Facebook</div>
                            <small class="text-muted">
                                @if(in_array('facebook', $linkedProviders)) Connected @else Not connected @endif
                            </small>
                        </div>
                    </div>
                    @if(in_array('facebook', $linkedProviders))
                        <form action="{{ route('profile.unlink-social', 'facebook') }}" method="POST" onsubmit="return confirm('Unlink your Facebook account?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-outline-primary btn-sm">Connect</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-key me-2 text-primary"></i>Change Password</h5>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
