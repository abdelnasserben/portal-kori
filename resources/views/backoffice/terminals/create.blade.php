@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Créer un terminal</h5>

        <div class="text-muted mb-3">
            Selon le contrat OpenAPI, la création terminal passe par <span class="mono">POST /api/v1/terminals</span>
            avec un body contenant <span class="mono">merchantCode</span>.
        </div>

        <form method="POST" action="{{ route('admin.terminals.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-5">
                <label class="form-label">Merchant code <span class="text-danger">*</span></label>
                <input name="merchantCode" class="form-control @error('merchantCode') is-invalid @enderror"
                    value="{{ old('merchantCode', $merchantCode) }}" maxlength="16" required>
                @error('merchantCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer le terminal</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.terminals.index') }}">Annuler</a>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-2">Modifier le statut d’un terminal</h6>
        <div class="text-muted mb-3">PATCH <span class="mono">/api/v1/terminals/{terminalUid}/status</span></div>

        <form method="POST" action="{{ route('admin.terminals.status.update') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-4">
                <label class="form-label">Terminal UID <span class="text-danger">*</span></label>
                <input name="terminalUid" class="form-control" value="{{ old('terminalUid') }}" maxlength="120" required>
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
