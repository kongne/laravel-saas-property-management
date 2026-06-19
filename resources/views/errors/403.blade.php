<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Forbidden - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center min-h-screen">
    <div class="max-w-lg mx-auto text-center px-6">
        <h1 class="text-8xl font-bold text-red-600 mb-2">403</h1>
        <div class="mb-6 flex justify-center">
            <svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m0 0v2m0-2h2m-2 0H10m9.364-7.364A9 9 0 114.636 4.636a9 9 0 0112.728 12.728z"/></svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-800 mb-2">Access Denied</h3>
        <p class="text-slate-500 mb-6">You don't have permission to access this resource.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Back to Dashboard
        </a>
    </div>
</body>
</html>
