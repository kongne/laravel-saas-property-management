<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Create Account</title>
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
        .password-hint { font-size: 0.75rem; }
        .password-checklist { list-style: none; padding: 0; margin: 0.5rem 0 0; font-size: 0.75rem; }
        .password-checklist li { margin-bottom: 0.25rem; color: #6c757d; }
        .password-checklist li.valid { color: #198754; }
        .password-checklist li.invalid { color: #dc3545; }
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
                    <div class="auth-header">
                        <h4 class="fw-bold mb-1">Create your account</h4>
                        <p class="text-muted mb-0">Start managing your properties in minutes</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label fw-medium small text-uppercase text-muted">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label fw-medium small text-uppercase text-muted">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="phone" class="form-label fw-medium small text-uppercase text-muted">Phone (optional)</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-medium small text-uppercase text-muted">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Create a strong password" required autocomplete="new-password">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-medium small text-uppercase text-muted">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                                </div>
                                <div class="col-12">
                                    <ul class="password-checklist" id="passwordChecklist">
                                        <li id="checkLength"><i class="bi bi-circle me-1"></i>At least 12 characters</li>
                                        <li id="checkUpper"><i class="bi bi-circle me-1"></i>One uppercase letter</li>
                                        <li id="checkLower"><i class="bi bi-circle me-1"></i>One lowercase letter</li>
                                        <li id="checkNumber"><i class="bi bi-circle me-1"></i>One number</li>
                                        <li id="checkSymbol"><i class="bi bi-circle me-1"></i>One special character</li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium small text-uppercase text-muted">I want to register as</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="role" id="roleLandlord" value="landlord" {{ old('role', 'landlord') === 'landlord' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="roleLandlord">
                                                <strong>Landlord</strong><br><small class="text-muted">Manage properties and tenants</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="role" id="roleTenant" value="tenant" {{ old('role') === 'tenant' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="roleTenant">
                                                <strong>Tenant</strong><br><small class="text-muted">View lease and pay rent</small>
                                            </label>
                                        </div>
                                    </div>
                                    @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('agree_terms') is-invalid @enderror" id="agree_terms" name="agree_terms" value="1">
                                        <label class="form-check-label small" for="agree_terms">I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a></label>
                                        @error('agree_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary w-100 btn-auth">Create Account <i class="bi bi-arrow-right ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Sign in</a></p>
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
        document.getElementById('password')?.addEventListener('input', function() {
            var v = this.value;
            function check(id, test) { var el = document.getElementById(id); if(!el) return; var ok = test(v); el.className = ok ? 'valid' : 'invalid'; el.innerHTML = (ok ? '<i class="bi bi-check-circle-fill me-1"></i>' : '<i class="bi bi-circle me-1"></i>') + el.textContent.trim().replace(/[^a-zA-Z0-9 .]/g, '').trim(); }
            check('checkLength', function(v){return v.length >= 12;});
            check('checkUpper', function(v){return /[A-Z]/.test(v);});
            check('checkLower', function(v){return /[a-z]/.test(v);});
            check('checkNumber', function(v){return /[0-9]/.test(v);});
            check('checkSymbol', function(v){return /[^a-zA-Z0-9]/.test(v);});
        });
    </script>
</body>
</html>
