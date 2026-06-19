<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Session Expired - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="display-1 fw-bold text-warning mb-0">419</h1>
                <div class="mb-4"><i class="bi bi-clock-history text-warning" style="font-size:4rem;"></i></div>
                <h3 class="fw-semibold mb-2">Session Expired</h3>
                <p class="text-muted mb-4">Your session has expired. Please log in again.</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4"><i class="bi bi-box-arrow-in-right me-2"></i>Log In</a>
            </div>
        </div>
    </div>
</body>
</html>
