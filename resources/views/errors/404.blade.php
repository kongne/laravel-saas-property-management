<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Not Found - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="display-1 fw-bold text-secondary mb-0">404</h1>
                <div class="mb-4"><i class="bi bi-compass text-secondary" style="font-size:4rem;"></i></div>
                <h3 class="fw-semibold mb-2">Page Not Found</h3>
                <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4"><i class="bi bi-house me-2"></i>Go Home</a>
            </div>
        </div>
    </div>
</body>
</html>
