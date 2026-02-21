@if(session('api_error'))
    @php($error = session('api_error'))

    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">
            Erreur API ({{ $error['status'] ?? 'N/A' }})
        </div>
        <div>
            {{ $error['message'] ?? 'Une erreur est survenue.' }}
        </div>
    </div>
@endif