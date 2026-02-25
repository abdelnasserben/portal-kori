@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Create a terminal</h5>

        <div class="text-muted mb-3">
            According to the OpenAPI contract, terminal creation goes through <span class="mono">POST /api/v1/terminals</span>
            avec un body contenant <span class="mono">merchantCode</span> et <span class="mono">displayName</span>.
        </div>

        <form method="POST" action="{{ route('admin.terminals.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-5">
                <x-form.input name="merchantCode" label="Merchant code" :value="old('merchantCode', $merchantCode)" maxlength="16" required />
            </div>

            <div class="col-12 col-md-7">
                <x-form.input name="displayName" label="Nom" :value="old('displayName')" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create terminal</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.terminals.index') }}">Annuler</a>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-2">Update terminal status</h6>
        <div class="text-muted mb-3">PATCH <span class="mono">/api/v1/terminals/{terminalUid}/status</span></div>

        <form method="POST" action="{{ route('admin.terminals.status.update') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-4">
                <x-form.input name="terminalUid" label="Terminal UID" :value="old('terminalUid')" maxlength="120" required />
            </div>

            <div class="col-12 col-md-3">
                <x-form.select name="targetStatus" label="Target status" :options="array_combine(['ACTIVE', 'SUSPENDED', 'CLOSED'], ['ACTIVE', 'SUSPENDED', 'CLOSED'])" :value="old('targetStatus')" required />
            </div>

            <div class="col-12 col-md-5">
                <x-form.input name="reason" label="Reason" :value="old('reason')" maxlength="255" />
            </div>

            <div class="col-12">
                <button class="btn btn-outline-primary" type="submit">Update status</button>
            </div>
        </form>
    </div>
@endsection
