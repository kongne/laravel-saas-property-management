<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ config('app.name') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
        .sidebar { width: 250px; min-height: 100vh; transition: transform 0.3s ease; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; z-index: 1050; transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1049; }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0 !important; }
        }
        .sidebar .nav-link { border-radius: 8px; margin-bottom: 2px; padding: 10px 16px; transition: all 0.2s; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.1); }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.2); font-weight: 600; }
        .toast-container { z-index: 9999; }
        .stat-card { transition: transform 0.2s; border: none; border-radius: 12px; }
        .stat-card:hover { transform: translateY(-2px); }
        .card { border-radius: 12px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: box-shadow 0.2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-loading { position: relative; pointer-events: none; color: transparent !important; }
        .btn-loading::after { content: ''; position: absolute; width: 1rem; height: 1rem; top: 50%; left: 50%; margin: -0.5rem; border: 2px solid; border-radius: 50%; border-color: #fff #fff transparent transparent; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .table th { font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; }
        .page-header { padding: 1.5rem 0; }
        .badge { font-weight: 500; padding: 0.35em 0.65em; }
    </style>
</head>
<body>
    <div class="d-flex" id="appLayout">
        @auth
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark sidebar" id="sidebar">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <i class="bi bi-building me-2 fs-4"></i>
                    <span class="fs-5 fw-semibold">{{ config('app.name') }}</span>
                </a>
                <button class="btn btn-link text-white d-md-none p-0" onclick="toggleSidebar()"><i class="bi bi-x-lg"></i></button>
            </div>
            <hr class="my-2">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
                <li>
                    <a href="{{ route('properties.index') }}" class="nav-link text-white {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i>Properties
                    </a>
                </li>
                <li>
                    <a href="{{ route('units.index') }}" class="nav-link text-white {{ request()->routeIs('units.*') ? 'active' : '' }}">
                        <i class="bi bi-door-open me-2"></i>Units
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenants.index') }}" class="nav-link text-white {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>Tenants
                    </a>
                </li>
                <li>
                    <a href="{{ route('leases.index') }}" class="nav-link text-white {{ request()->routeIs('leases.*') ? 'active' : '' }}">
                        <i class="bi bi-file-text me-2"></i>Leases
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('payments.index') }}" class="nav-link text-white {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card me-2"></i>Payments
                    </a>
                </li>
                <li>
                    <a href="{{ route('maintenance.index') }}" class="nav-link text-white {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                        <i class="bi bi-tools me-2"></i>Maintenance
                    </a>
                </li>
            </ul>
            <hr class="my-2">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2 fs-5"></i>
                    <strong class="small">{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span></li>
                    <li><span class="dropdown-item-text text-muted small">Role: {{ ucfirst(Auth::user()->role) }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="{{ route('profile.security') }}" class="dropdown-item"><i class="bi bi-shield-check me-2"></i>Security</a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @endauth

        <div class="flex-grow-1 main-content" @auth style="margin-left:0" @endauth>
            @auth
            <nav class="bg-white shadow-sm px-4 py-2 d-flex align-items-center d-md-none sticky-top">
                <button class="btn btn-link text-dark p-0 me-3" onclick="toggleSidebar()"><i class="bi bi-list fs-4"></i></button>
                <span class="fw-semibold">{{ config('app.name') }}</span>
            </nav>
            @endauth

            <div class="p-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                var btn = this.querySelector('[type="submit"]');
                if (btn && !btn.classList.contains('no-loading')) {
                    btn.classList.add('btn-loading');
                    btn.disabled = true;
                }
            });
        });
        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                var bs = new bootstrap.Alert(el);
                setTimeout(function() { bs.close(); }, 5000);
            });
        }, 100);

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    reg.addEventListener('updatefound', function() {
                        var installing = reg.installing;
                        installing.addEventListener('statechange', function() {
                            if (installing.state === 'installed' && navigator.serviceWorker.controller) {
                                var toast = document.getElementById('toastContainer');
                                if (toast) {
                                    toast.innerHTML = '<div class="toast show align-items-center text-bg-info border-0" role="alert"><div class="d-flex"><div class="toast-body"><strong>Updated!</strong> New version available. <button class="btn btn-sm btn-light ms-2" onclick="location.reload()">Refresh</button></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
                                }
                            }
                        });
                    });
                }).catch(function() {});
            });
        }

        if (!navigator.onLine) {
            document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f4f6f9;text-align:center;padding:20px;"><div><div style="width:80px;height:80px;background:#e9ecef;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:24px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1" fill="#6c757d"/></svg></div><h2 class="fw-bold mb-2">You\'re Offline</h2><p class="text-muted mb-4">Please check your connection.</p><button onclick="location.reload()" class="btn btn-primary px-4 rounded-pill">Try Again</button></div></div>';
        }

        window.addEventListener('online', function() { location.reload(); });
        window.addEventListener('offline', function() {
            var c = document.getElementById('toastContainer');
            if (c) { c.innerHTML = '<div class="toast show align-items-center text-bg-warning border-0" role="alert"><div class="d-flex"><div class="toast-body"><i class="bi bi-wifi-off me-2"></i>You are offline. Some features may be unavailable.</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>'; }
        });
    </script>
    @stack('scripts')
</body>
</html>
