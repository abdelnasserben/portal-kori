@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-2">Créer un admin</h5>

        <div class="text-muted mb-3">
            Selon le contrat OpenAPI, la création d'admin passe par <span class="mono">POST /api/v1/admins</span>
            avec un body contenant <span class="mono">username</span>.
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

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer l'admin</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.home') }}">Annuler</a>
            </div>
        </form>
    </div>
@endsection
