@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-2">Create a terminal</h5>

        <div class="text-muted mb-3">
            A registered payment device used by a merchant to securely accept transactions through platform.
        </div>

        <form method="POST" action="{{ route('admin.terminals.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-5">
                <x-form.input name="merchantCode" label="Merchant code" :value="old('merchantCode', $merchantCode)" maxlength="16" required />
            </div>

            <div class="col-12 col-md-7">
                <x-form.input name="displayName" label="Name" :value="old('displayName')" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.terminals.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
