@extends('layouts.app')

@section('content')
<div class="card p-4">
    <h5 class="fw-semibold mb-2">Error while calling the API</h5>

    <div class="mb-3">
        <span class="badge text-bg-danger">HTTP {{ $error['status'] ?? 'N/A' }}</span>
    </div>

    <div class="mb-3">
        {{ $error['message'] ?? 'An error occurred.' }}
    </div>
</div>
@endsection