@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Create an admin</h5>

        <div class="text-muted mb-3">
            According to the OpenAPI contract, admin creation goes through <span class="mono">POST /api/v1/admins</span>
            avec un body contenant <span class="mono">username</span> et <span class="mono">displayName</span>.
        </div>

        <form method="POST" action="{{ route('admin.admins.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-6">
                <x-form.input name="username" label="Username" :value="old('username')" maxlength="64" required />
            </div>

            <div class="col-12 col-md-6">
                <x-form.input name="displayName" label="Nom" :value="old('displayName')" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create admin</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.admins.index') }}">Annuler</a>
            </div>
        </form>
    </div>
    <div class="card p-4">
        <h6 class="fw-semibold mb-2">Update admin status</h6>
        <div class="text-muted mb-3">PATCH <span class="mono">/api/v1/admins/{adminUsername}/status</span></div>

        <form method="POST" action="{{ route('admin.admins.status.update') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-4">
                <x-form.input name="adminUsername" label="Username" :value="old('adminUsername')" maxlength="64" required />
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
