<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Two-Factor Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); max-width: 440px; margin: 0 auto; }
        .auth-body { padding: 2.5rem; }
        .code-input { width: 100%; text-align: center; font-size: 2rem; font-weight: 700; letter-spacing: 12px; padding: 16px; border-radius: 12px; border: 2px solid #e9ecef; font-family: 'Courier New', monospace; }
        .code-input:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,0.1); }
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
                            <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                <i class="bi bi-shield-lock fs-2 text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-1">Two-Factor Authentication</h4>
                            <p class="text-muted small mb-0">Enter the 6-digit code sent to your email</p>
                        </div>

                        <form method="POST" action="{{ route('two-factor.verify') }}">
                            @csrf
                            <div class="mb-4">
                                <input type="text" class="form-control code-input @error('code') is-invalid @enderror" id="code" name="code" placeholder="000000" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" autofocus required>
                                @error('code')
                                    <div class="invalid-feedback text-center">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold" style="border-radius: 12px;">
                                <i class="bi bi-shield-check me-2"></i>Verify Code
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-2">Didn't receive the code?</p>
                            <a href="{{ route('two-factor.challenge') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 50px;">
                                <i class="bi bi-arrow-clockwise me-1"></i>Resend Code
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('two-factor.recovery') }}" class="text-decoration-none small">
                                <i class="bi bi-key me-1"></i>Use a recovery code
                            </a>
                        </div>

                        <hr class="my-4">
                        <div class="text-center">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                                    <i class="bi bi-box-arrow-left me-1"></i>Cancel and sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('code')?.addEventListener('input', function(e) { this.value = this.value.replace(/\D/g, '').slice(0, 6); });
        document.querySelectorAll('form').forEach(function(f) { f.addEventListener('submit', function(e) { var btn = this.querySelector('[type="submit"]'); if(btn) { btn.classList.add('btn-loading'); btn.disabled = true; } }); });
    </script>
</body>
</html>
