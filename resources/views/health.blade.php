@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <h5 class="fw-semibold mb-3">API Health</h5>

        <pre class="mono mb-0">{{ json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        <div class="mt-3">
            <a href="{{ route('auth.success') }}" class="btn btn-primary w-auto">Go to success</a>
        </div>
    </div>
@endsection
