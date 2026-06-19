<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); }
        .auth-header { padding: 2rem 2rem 0; }
        .auth-body { padding: 2rem; }
        .btn-auth { padding: 12px 20px; border-radius: 12px; font-weight: 600; }
        .form-control { border-radius: 10px; padding: 12px 16px; border: 2px solid #e9ecef; transition: all 0.2s; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,0.1); }
        .brand-icon { width: 48px; height: 48px; background: #0d6efd; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none">
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="brand-icon"><i class="bi bi-building fs-4 text-white"></i></div>
                            <span class="fs-4 fw-bold text-dark">{{ config('app.name') }}</span>
                        </div>
                    </a>
                </div>
                <div class="card auth-card">
                    <div class="auth-header">
                        <h4 class="fw-bold mb-1">Forgot your password?</h4>
                        <p class="text-muted mb-0">Enter your email and we'll send you a reset link</p>
                    </div>
                    <div class="auth-body">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium small text-uppercase text-muted">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-auth">
                                <i class="bi bi-envelope me-2"></i>Send Reset Link
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Back to sign in
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-center text-muted small mt-4">
                    <a href="/" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to home</a>
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('btn-loading'); btn.disabled = true; } }); });
    </script>
</body>
</html>
