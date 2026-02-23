@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Créer un admin</h5>

        <div class="text-muted mb-3">
            Selon le contrat OpenAPI, la création d'admin passe par <span class="mono">POST /api/v1/admins</span>
            avec un body contenant <span class="mono">username</span> et <span class="mono">displayName</span>.
        </div>

        <form method="POST" action="{{ route('admin.admins.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-6">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}" maxlength="64" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input name="displayName" class="form-control @error('displayName') is-invalid @enderror"
                    value="{{ old('displayName') }}" maxlength="120" required>
                @error('displayName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer l'admin</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.admins.index') }}">Annuler</a>
            </div>
        </form>
    </div>
    <div class="card p-4">
        <h6 class="fw-semibold mb-2">Modifier le statut d’un admin</h6>
        <div class="text-muted mb-3">PATCH <span class="mono">/api/v1/admins/{adminUsername}/status</span></div>

        <form method="POST" action="{{ route('admin.admins.status.update') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-4">
                <label class="form-label">Admin username <span class="text-danger">*</span></label>
                <input name="adminUsername" class="form-control" value="{{ old('adminUsername') }}" maxlength="64" required>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Target status <span class="text-danger">*</span></label>
                <select name="targetStatus" class="form-select" required>
                    @foreach (['ACTIVE', 'SUSPENDED', 'CLOSED'] as $status)
                        <option value="{{ $status }}" @selected(old('targetStatus') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-5">
                <label class="form-label">Reason (optionnel)</label>
                <input name="reason" class="form-control" value="{{ old('reason') }}" maxlength="255">
            </div>

            <div class="col-12">
                <button class="btn btn-outline-primary" type="submit">Mettre à jour le statut</button>
            </div>
        </form>
    </div>
@endsection
