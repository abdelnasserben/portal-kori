@if(session('api_error'))
    @php($error = session('api_error'))

    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">
            API Error ({{ $error['status'] ?? 'N/A' }})
        </div>
        <div>
            {{ $error['message'] ?? 'An error occurred.' }}
        </div>
    </div>
@endif