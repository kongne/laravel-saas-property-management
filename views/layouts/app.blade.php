<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd" id="metaThemeColor">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512x512.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root { --sidebar-width: 260px; --transition-speed: 0.3s; --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        [data-bs-theme="dark"] { --bs-body-bg: #0a0a0f; --bs-body-color: #e2e8f0; --bs-card-bg: #14141a; --bs-card-border-color: #1e1e26; --bs-border-color: #1e1e26; }
        [data-bs-theme="dark"] .sidebar { background: linear-gradient(180deg, #0a0a12 0%, #14141a 100%) !important; }
        [data-bs-theme="dark"] .navbar-top { background: #14141a !important; border-color: #1e1e26 !important; }
        [data-bs-theme="dark"] .table { --bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.03); }
        [data-bs-theme="dark"] .dropdown-menu { background: #14141a; border-color: #1e1e26; }
        [data-bs-theme="dark"] .dropdown-item { color: #e2e8f0; }
        [data-bs-theme="dark"] .dropdown-item:hover { background: #1e1e26; }
        [data-bs-theme="dark"] .stat-card { background: #14141a !important; }
        [data-bs-theme="dark"] .card { background: #14141a; }
        [data-bs-theme="dark"] .text-bg-primary { background: #1e40af !important; }
        [data-bs-theme="dark"] .text-bg-success { background: #166534 !important; }
        [data-bs-theme="dark"] .text-bg-warning { background: #854d0e !important; }
        [data-bs-theme="dark"] .text-bg-info { background: #075985 !important; }
        [data-bs-theme="dark"] .table th { color: #94a3b8; }
        [data-bs-theme="dark"] .alert { background: #14141a !important; }
        [data-bs-theme="dark"] .chart-container canvas { filter: brightness(0.9); }

        body { font-family: 'Inter', sans-serif; background: var(--bs-body-bg, #f1f5f9); transition: background var(--transition-speed), color var(--transition-speed); }
        .sidebar { width: var(--sidebar-width); min-height: 100vh; background: var(--sidebar-bg); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background var(--transition-speed); position: fixed; z-index: 1030; overflow-y: auto; }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
        .main-content { margin-left: var(--sidebar-width); transition: margin var(--transition-speed); min-height: 100vh; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1029; }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0 !important; }
        }
        .sidebar .nav-link { border-radius: 10px; margin-bottom: 2px; padding: 10px 16px; transition: all 0.2s ease; font-size: 0.875rem; font-weight: 500; color: rgba(255,255,255,0.7); }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.08); color: #fff; transform: translateX(2px); }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.12); color: #fff; font-weight: 600; box-shadow: inset 3px 0 0 #3b82f6; }
        .sidebar .nav-link i { font-size: 1.1rem; width: 1.5rem; text-align: center; }
        .navbar-top { background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); border-bottom: 1px solid #e2e8f0; }
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: none; border-radius: 16px; overflow: hidden; cursor: default; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
        .card { border-radius: 14px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.3s ease; }
        .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .card-header { background: transparent; border-bottom: 1px solid var(--bs-border-color, #e2e8f0); padding: 1rem 1.25rem; }
        .card-header h5 { margin: 0; font-weight: 600; }
        .btn { border-radius: 10px; font-weight: 500; transition: all 0.2s ease; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.4); }
        .btn-sm { border-radius: 8px; }
        .btn-loading { position: relative; pointer-events: none; color: transparent !important; }
        .btn-loading::after { content: ''; position: absolute; width: 1rem; height: 1rem; top: 50%; left: 50%; margin: -0.5rem; border: 2px solid; border-radius: 50%; border-color: #fff #fff transparent transparent; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .table th { font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; border-top: none; white-space: nowrap; padding: 0.75rem 0.5rem; }
        .table td { vertical-align: middle; font-size: 0.875rem; padding: 0.75rem 0.5rem; }
        .badge { font-weight: 500; padding: 0.3em 0.7em; font-size: 0.75rem; border-radius: 6px; }
        .page-enter { animation: pageEnter 0.35s ease; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .skeleton { background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 6px; }
        [data-bs-theme="dark"] .skeleton { background: linear-gradient(90deg, #1e1e26 25%, #27272a 50%, #1e1e26 75%); background-size: 200% 100%; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .empty-state { padding: 3rem 1rem; text-align: center; animation: fadeIn 0.4s ease; }
        .empty-state svg { width: 120px; height: 120px; margin-bottom: 1rem; opacity: 0.4; }
        .toast-container { z-index: 9999; }
        .search-box { position: relative; }
        .search-box .bi-search { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1; }
        .search-box input { padding-left: 36px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.875rem; }
        .search-box input:focus { background: #fff; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        [data-bs-theme="dark"] .search-box input { background: #1a1a22; border-color: #27272a; color: #e2e8f0; }
        [data-bs-theme="dark"] .search-box input:focus { background: #1a1a22; border-color: #3b82f6; }
        .fade-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; }
        .dropdown-menu { border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; animation: fadeIn 0.15s ease; }
        .dropdown-item { border-radius: 8px; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .modal-content { border: none; border-radius: 16px; box-shadow: 0 25px 60px rgba(0,0,0,0.15); }
        .modal-header { border-bottom: none; padding: 1.5rem 1.5rem 0; }
        .modal-body { padding: 1rem 1.5rem; }
        .modal-footer { border-top: none; padding: 0 1.5rem 1.5rem; }
        .page-header { padding: 1rem 0; }
        .chart-container { position: relative; height: 260px; }
        .action-btns .btn { margin-right: 0.25rem; }
        .action-btns .btn:last-child { margin-right: 0; }
        .gradient-text { background: linear-gradient(135deg, #3b82f6, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .alert { border-radius: 12px; }
        .form-control, .form-select { border-radius: 10px; font-size: 0.875rem; }
        .form-control:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .pagination { gap: 0.25rem; }
        .page-link { border-radius: 8px !important; border: none; color: #475569; font-weight: 500; padding: 0.4rem 0.8rem; }
        .page-item.active .page-link { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
        .table-hover > tbody > tr { transition: background 0.15s ease; }
        .border-start { border-width: 3px !important; border-radius: 12px !important; }
        .profile-avatar { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem; }
    </style>
</head>
<body>
    <div class="d-flex" id="appLayout">
        @auth
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <div class="d-flex flex-column flex-shrink-0 p-3 sidebar" id="sidebar">
            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <div class="profile-avatar bg-primary bg-opacity-25 me-2"><i class="bi bi-building text-primary"></i></div>
                    <span class="fs-5 fw-bold gradient-text">{{ config('app.name') }}</span>
                </a>
                <button class="btn btn-link text-white p-0 d-md-none opacity-75" onclick="toggleSidebar()"><i class="bi bi-x-lg fs-5"></i></button>
            </div>
            <hr class="my-2 opacity-25">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
                <li>
                    <a href="{{ route('properties.index') }}" class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i>Properties
                    </a>
                </li>
                <li>
                    <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                        <i class="bi bi-door-open me-2"></i>Units
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>Tenants
                    </a>
                </li>
                <li>
                    <a href="{{ route('leases.index') }}" class="nav-link {{ request()->routeIs('leases.*') ? 'active' : '' }}">
                        <i class="bi bi-file-text me-2"></i>Leases
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card me-2"></i>Payments
                    </a>
                </li>
                <li>
                    <a href="{{ route('maintenance.index') }}" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                        <i class="bi bi-tools me-2"></i>Maintenance
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->isLandlord())
                <li>
                    <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i>Audit Trail
                    </a>
                </li>
                @endif
            </ul>
            <hr class="my-2 opacity-25">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" style="padding: 8px 12px; border-radius: 12px; transition: background 0.2s;">
                    <div class="profile-avatar bg-primary bg-opacity-25 me-2">
                        <span class="text-primary">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate" style="color: rgba(255,255,255,0.95)">{{ Auth::user()->name }}</div>
                        <div class="text-white-50" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow mx-2">
                    <li><span class="dropdown-item-text text-white-50 small px-2">{{ Auth::user()->email }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a href="{{ route('profile.security') }}" class="dropdown-item"><i class="bi bi-shield-check me-2"></i>Security</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @endauth

        <div class="flex-grow-1 main-content">
            @auth
            <nav class="navbar-top px-4 py-2 d-flex align-items-center justify-content-between sticky-top">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-link text-body p-0 d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-4"></i></button>
                    <div class="search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control form-control-sm" id="globalSearch" placeholder="Search anything..." style="width:320px">
                        <div class="dropdown-menu w-100" id="searchResults" style="max-height:400px;overflow-y:auto"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-icon btn-outline-secondary" onclick="toggleDarkMode()" title="Toggle theme">
                        <i class="bi bi-moon-stars" id="darkModeIcon"></i>
                    </button>
                    <a href="{{ route('profile.security') }}" class="btn btn-icon btn-outline-secondary" title="Security Settings">
                        <i class="bi bi-shield-check"></i>
                    </a>
                </div>
            </nav>
            @endauth

            <div class="p-4 page-enter">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i><div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i><div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-x-circle-fill me-2 fs-5"></i><strong>Please fix:</strong>
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i><div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="mb-3" style="font-size:3rem; line-height:1;">⚠️</div>
                    <h5 class="fw-bold mb-1" id="confirmTitle">Confirm Action</h5>
                    <p class="text-muted mb-0" id="confirmMessage">Are you sure?</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" id="confirmCancelBtn">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme:dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        })();

        function toggleDarkMode() {
            var html = document.documentElement;
            var isDark = html.getAttribute('data-bs-theme') === 'dark';
            html.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            document.getElementById('metaThemeColor').content = isDark ? '#f1f5f9' : '#0f172a';
        }
        document.addEventListener('DOMContentLoaded', function() {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            var icon = document.getElementById('darkModeIcon');
            if (icon) icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
        });

        var confirmForm = null;
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (confirmForm) { confirmForm.submit(); confirmForm = null; }
        });
        document.querySelectorAll('[data-confirm]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                confirmForm = this.closest('form');
                if (!confirmForm) { var a = document.createElement('a'); a.href = this.href; confirmForm = a; }
                var msg = this.getAttribute('data-confirm') || 'Are you sure?';
                var title = this.getAttribute('data-confirm-title') || 'Confirm Action';
                document.getElementById('confirmTitle').textContent = title;
                document.getElementById('confirmMessage').textContent = msg;
                new bootstrap.Modal(document.getElementById('confirmModal')).show();
            });
        });

        document.getElementById('confirmCancelBtn').addEventListener('click', function() { confirmForm = null; });
        document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() { confirmForm = null; });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                var btn = this.querySelector('[type="submit"]');
                if (btn && !btn.classList.contains('no-loading')) {
                    btn.classList.add('btn-loading');
                    btn.disabled = true;
                }
            });
        });

        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                var bs = bootstrap.Alert.getOrCreateInstance(el);
                setTimeout(function() { try { bs.close(); } catch(e) {} }, 6000);
            });
        }, 100);

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    reg.addEventListener('updatefound', function() {
                        reg.installing.addEventListener('statechange', function() {
                            if (this.state === 'installed' && navigator.serviceWorker.controller) {
                                showToast('Updated! New version available. <button class="btn btn-sm btn-light ms-2" onclick="location.reload()">Refresh</button>', 'info');
                            }
                        });
                    });
                }).catch(function() {});
            });
        }

        if (!navigator.onLine) {
            document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bs-body-bg,#f1f5f9);text-align:center;padding:20px;"><div><div style="width:80px;height:80px;background:var(--bs-card-bg,#e2e8f0);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:24px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1" fill="#64748b"/></svg></div><h2 class="fw-bold mb-2">You\'re Offline</h2><p class="text-muted mb-4">Please check your connection and try again.</p><button onclick="location.reload()" class="btn btn-primary px-4 rounded-pill">Try Again</button></div></div>';
        }
        window.addEventListener('online', function() { location.reload(); });
        window.addEventListener('offline', function() {
            showToast('<i class="bi bi-wifi-off me-2"></i>You are offline. Some features may be unavailable.', 'warning');
        });

        function showToast(html, type) {
            var c = document.getElementById('toastContainer');
            if (!c) return;
            var bg = type === 'warning' ? 'text-bg-warning' : type === 'info' ? 'text-bg-info' : type === 'error' ? 'text-bg-danger' : 'text-bg-success';
            c.innerHTML = '<div class="toast show align-items-center ' + bg + ' border-0" role="alert"><div class="d-flex"><div class="toast-body">' + html + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
        }

        document.getElementById('globalSearch')?.addEventListener('input', function() {
            var q = this.value.trim();
            var dd = document.getElementById('searchResults');
            if (q.length < 2) { dd.classList.remove('show'); return; }
            fetch('/api/search?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    dd.innerHTML = '';
                    if (!data.results || !data.results.length) {
                        dd.innerHTML = '<div class="dropdown-item text-muted">No results</div>';
                    } else {
                        data.results.forEach(function(item) {
                            var a = document.createElement('a');
                            a.href = item.url;
                            a.className = 'dropdown-item d-flex align-items-center gap-2';
                            a.innerHTML = '<i class="bi bi-' + item.icon + '"></i> <div><div>' + item.label + '</div><small class="text-muted">' + (item.subtext || '') + '</small></div>';
                            dd.appendChild(a);
                        });
                    }
                    dd.classList.add('show');
                }).catch(function() {});
        });
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-box')) document.getElementById('searchResults')?.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
