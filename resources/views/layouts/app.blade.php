<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Kori Portal') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-tune.css') }}">

    <style>
        .container-portal {
            max-width: 1200px;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light bg-white px-4 py-3">
        <div class="w-100">
            @php($roles = app(\App\Services\Auth\RoleService::class))
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="navbar-brand fw-semibold m-0">Kori Portal</span>

                @if ($roles->has('ADMIN'))
                    <form method="GET" action="{{ route('admin.lookups.index') }}"
                        class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 520px;">
                        <input name="q" class="form-control form-control-sm" value="{{ request('q') }}" required
                            placeholder="Recherche globale (client, marchand, agent, terminal, admin)">
                        <button class="btn btn-sm btn-primary" type="submit">Lookup</button>
                    </form>
                @endif

                <div class="d-flex gap-2 flex-wrap ms-auto">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('home') }}">Home</a>

                    @if ($roles->has('ADMIN'))
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.home') }}">Admin</a>
                    @endif

                    @if ($roles->has('MERCHANT'))
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('merchant.home') }}">Merchant</a>
                    @endif

                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('health') }}">API Health</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container container-portal py-4">
        @include('components.api-error-banner')
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
