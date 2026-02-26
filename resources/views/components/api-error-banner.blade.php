@if (session('api_error'))
    @php
        $error = session('api_error');
        $payload = $error['payload'] ?? [];
        $correlationId = $payload['correlationId'] ?? ($payload['correlationID'] ?? null);
        $errorId = $payload['errorId'] ?? ($payload['errorID'] ?? null);
    @endphp

    <div class="alert app-error-banner mb-4" role="alert">
        <div class="fw-semibold mb-1">API request failed ({{ $error['status'] ?? 'N/A' }})</div>
        <div class="mb-2">{{ $error['message'] ?? 'An error occurred while contacting API.' }}</div>
        <div class="small text-muted d-flex flex-wrap gap-3">
            @if ($correlationId)
                <span>Correlation ID: <span class="mono">{{ $correlationId }}</span></span>
            @endif
            @if ($errorId)
                <span>Error ID: <span class="mono">{{ $errorId }}</span></span>
            @endif
        </div>
    </div>
@endif
