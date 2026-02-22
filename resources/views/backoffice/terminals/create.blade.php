@extends('layouts.app')

@section('content')
    <div class="card p-4">
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
                <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
