<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Recovery Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); max-width: 440px; margin: 0 auto; }
        .auth-body { padding: 2.5rem; }
        .brand-icon { width: 48px; height: 48px; background: #0d6efd; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none">
                        <div class="d-inline-flex align-items-center gap-2">
                            <div class="brand-icon"><i class="bi bi-building fs-4 text-white"></i></div>
                            <span class="fs-4 fw-bold text-dark">{{ config('app.name') }}</span>
                        </div>
                    </a>
                </div>
                <div class="card auth-card">
                    <div class="auth-body">
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="bi bi-key fs-2 text-warning"></i>
                            </div>
                            <h4 class="fw-bold mb-1">Recovery Code</h4>
                            <p class="text-muted small mb-0">Use a recovery code to access your account</p>
                        </div>

                        <form method="POST" action="{{ route('two-factor.recovery.verify') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="recovery_code" class="form-label small fw-medium">Recovery Code</label>
                                <input type="text" class="form-control @error('recovery_code') is-invalid @enderror" id="recovery_code" name="recovery_code" placeholder="XXXX-XXXX-XXXX-XXXX" required autofocus>
                                @error('recovery_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-warning w-100 py-3 fw-semibold" style="border-radius: 12px;">
                                <i class="bi bi-unlock me-2"></i>Verify Recovery Code
                            </button>
                        </form>

                        <hr class="my-4">
                        <div class="text-center">
                            <a href="{{ route('two-factor.challenge') }}" class="text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i>Back to 2FA code
                            </a>
                            <span class="text-muted mx-2">|</span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted text-decoration-none small p-0">
                                    <i class="bi bi-box-arrow-left me-1"></i>Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('btn-loading'); btn.disabled = true; } }); });</script>
</body>
</html>
