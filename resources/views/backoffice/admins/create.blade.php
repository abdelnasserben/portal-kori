@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card panel p-4 mb-3">
        <h5 class="fw-semibold mb-2">Create an admin</h5>

        <div class="text-muted mb-3">
            A backoffice user who manages, monitors, and configures the platform.
        </div>

        <form method="POST" action="{{ route('admin.admins.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-6">
                <x-form.input name="username" label="Username" :value="old('username')" maxlength="64" required />
            </div>

            <div class="col-12 col-md-6">
                <x-form.input name="displayName" label="Name" :value="old('displayName')" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.admins.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
