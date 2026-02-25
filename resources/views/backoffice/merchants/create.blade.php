@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Create a merchant</h5>

        <div class="text-muted mb-3">
            According to the OpenAPI contract, merchant creation goes through <span class="mono">POST /api/v1/merchants</span>
            avec un body contenant <span class="mono">displayName</span>.
        </div>

        <form method="POST" action="{{ route('admin.merchants.store') }}">
            @csrf
            <div class="col-12 col-md-6">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input name="displayName" class="form-control @error('displayName') is-invalid @enderror"
                    value="{{ old('displayName') }}" maxlength="120" required>
                @error('displayName')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.index') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
