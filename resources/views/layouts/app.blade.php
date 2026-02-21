<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Kori Portal') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar { border-bottom: 1px solid #e5e7eb; }
        .container-portal { max-width: 1200px; }
        .card {
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            box-shadow: none;
        }
        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white px-4">
    <span class="navbar-brand fw-semibold">Kori Portal</span>
</nav>

<div class="container container-portal py-4">
    @include('components.api-error-banner')
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>