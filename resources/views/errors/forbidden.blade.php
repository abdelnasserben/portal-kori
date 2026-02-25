@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h5 class="fw-semibold mb-2">Access denied</h5>

    <div class="text-muted mb-3">
        You do not have the required permissions to access this page.
    </div>

    <div class="mb-3">
        <div class="fw-semibold">Required roles</div>
        <div class="mono">{{ implode(', ', $required ?? []) }}</div>
    </div>

    <div class="mb-3">
        <div class="fw-semibold">Your roles</div>
        <div class="mono">{{ implode(', ', $roles ?? []) }}</div>
    </div>

    <a class="btn btn-outline-secondary" href="{{ route('auth.success') }}">Back</a>
</div>
@endsection