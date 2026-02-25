@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Request a client refund</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Back</a>
        </div>

        <p class="text-muted small">
            The amount is calculated by the API based on business rules.
        </p>

        <form method="POST" action="{{ route('admin.client-refunds.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Client code</label>
                <input name="clientCode" class="form-control @error('clientCode') is-invalid @enderror"
                    value="{{ old('clientCode', $prefillClientCode ?? '') }}" required maxlength="16"
                    placeholder="Client code">
                @error('clientCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create request</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.clients.index') }}">View clients</a>
            </div>
        </form>
    </div>
@endsection
