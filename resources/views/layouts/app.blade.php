<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Kori Portal') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-tune.css') }}">
</head>

<body>
    @php($roles = app(\App\Services\Auth\RoleService::class))

    <header class="topbar sticky-top bg-white">
        <nav class="navbar navbar-expand-lg px-3 px-lg-4 py-3">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Kori Portal</a>

            @if ($roles->has('ADMIN'))
                <form method="GET" action="{{ route('admin.lookups.index') }}"
                    class="d-flex ms-lg-4 me-auto mt-2 mt-lg-0" style="max-width: 560px; width: 100%;">
                    <input name="q" class="form-control form-control-sm" value="{{ request('q') }}" required
                        placeholder="Global search: client, merchant, agent, terminal, admin">
                </form>
            @endif

            <div class="d-flex align-items-center gap-2 ms-auto">
                <a class="btn btn-sm {{ request()->routeIs('home') ? 'btn-primary' : 'btn-outline-secondary' }}"
                    href="{{ route('home') }}">Dashboard</a>
                <a class="btn btn-sm {{ request()->routeIs('health') ? 'btn-primary' : 'btn-outline-secondary' }}"
                    href="{{ route('health') }}">API Health</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit">Sign out</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="container-fluid py-4">
        <div class="row g-4">
            <aside class="col-12 col-lg-2">
                <div class="panel p-2">
                    <nav class="nav nav-pills flex-lg-column gap-1">
                        @if ($roles->has('ADMIN'))
                            <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}"
                                href="{{ route('admin.transactions.index') }}">Transactions</a>
                            <a class="nav-link {{ request()->routeIs('admin.audits.*') ? 'active' : '' }}"
                                href="{{ route('admin.audits.index') }}">Audits</a>
                            <a class="nav-link {{ request()->routeIs('admin.merchants.*') ? 'active' : '' }}"
                                href="{{ route('admin.merchants.index') }}">Merchants</a>
                            <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"
                                href="{{ route('admin.clients.index') }}">Clients</a>
                            <a class="nav-link {{ request()->routeIs('admin.terminals.*') ? 'active' : '' }}"
                                href="{{ route('admin.terminals.index') }}">Terminals</a>
                            <a class="nav-link {{ request()->routeIs('admin.cards.*') ? 'active' : '' }}"
                                href="{{ route('admin.cards.index') }}">Cards</a>
                            <a class="nav-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}"
                                href="{{ route('admin.agents.index') }}">Agents</a>
                            <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                                href="{{ route('admin.admins.index') }}">Admins</a>
                            <a class="nav-link {{ request()->routeIs('admin.account-profiles.*') ? 'active' : '' }}"
                                href="{{ route('admin.account-profiles.index') }}">Account Profiles</a>
                            <a class="nav-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}"
                                href="{{ route('admin.config.index') }}">Settings</a>
                            <a class="nav-link {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}"
                                href="{{ route('admin.payouts.create') }}">Payout Requests</a>
                            <a class="nav-link {{ request()->routeIs('admin.client-refunds.*') ? 'active' : '' }}"
                                href="{{ route('admin.client-refunds.create') }}">Client Refund Requests</a>
                            <a class="nav-link {{ request()->routeIs('admin.ledger.*') ? 'active' : '' }}"
                                href="{{ route('admin.ledger.index') }}">Ledger</a>
                        @endif
                        @if ($roles->has('MERCHANT'))
                            <a class="nav-link {{ request()->routeIs('merchant.me.transactions') || request()->routeIs('merchant.me.transactions.show') ? 'active' : '' }}"
                                href="{{ route('merchant.me.transactions') }}">Transactions</a>
                            <a class="nav-link {{ request()->routeIs('merchant.me.terminals') || request()->routeIs('merchant.me.terminals.show') ? 'active' : '' }}"
                                href="{{ route('merchant.me.terminals') }}">Terminals</a>
                            <a class="nav-link {{ request()->routeIs('merchant.me.profile') ? 'active' : '' }}"
                                href="{{ route('merchant.me.profile') }}">Profile</a>
                            <a class="nav-link {{ request()->routeIs('merchant.me.balance') ? 'active' : '' }}"
                                href="{{ route('merchant.me.balance') }}">Balance</a>
                        @endif
                    </nav>
                </div>
            </aside>

            <main class="col-12 col-lg-10">
                @include('components.api-error-banner')
                @yield('content')
            </main>
        </div>
    </div>

    @include('components.confirm-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (() => {
            document.addEventListener('click', async (e) => {
                const copyBtn = e.target.closest('[data-copy-value]');
                if (!copyBtn) return;

                try {
                    await navigator.clipboard.writeText(copyBtn.getAttribute('data-copy-value') ?? '');
                    const oldText = copyBtn.innerText;
                    copyBtn.innerText = 'Copied';
                    setTimeout(() => copyBtn.innerText = oldText, 900);
                } catch (err) {
                    copyBtn.innerText = 'Unavailable';
                    setTimeout(() => copyBtn.innerText = 'Copy', 900);
                }
            });

            const confirmModalEl = document.getElementById('confirmActionModal');
            const confirmMessageEl = confirmModalEl?.querySelector('[data-confirm-message]');
            const confirmSubmitBtn = document.getElementById('confirmActionSubmit');
            let pendingForm = null;

            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) return;
                e.preventDefault();

                pendingForm = form;
                if (confirmMessageEl) {
                    confirmMessageEl.textContent = form.getAttribute('data-confirm-message') ||
                        'Are you sure you want to continue?';
                }

                bootstrap.Modal.getOrCreateInstance(confirmModalEl).show();
            });

            confirmSubmitBtn?.addEventListener('click', () => {
                if (!pendingForm) return;
                const form = pendingForm;
                pendingForm = null;
                bootstrap.Modal.getOrCreateInstance(confirmModalEl).hide();
                form.submit();
            });
        })();
    </script>
</body>

</html>
