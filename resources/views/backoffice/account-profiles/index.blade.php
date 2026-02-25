@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Account profile management</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.home') }}">Back</a>
        </div>

        <p class="text-muted mb-1" style="font-size:.9rem;">Administration action..</p>

    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-3">Update profile status</h6>

        <form method="POST" action="{{ route('admin.account-profiles.status.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Account type</label>
                <input name="accountType" class="form-control form-control-sm" required maxlength="50"
                    placeholder="Account type" value="{{ old('accountType') }}">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Owner ref</label>
                <input name="ownerRef" class="form-control form-control-sm" required maxlength="120"
                    placeholder="Actor ref" value="{{ old('ownerRef') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">New status</label>
                <input name="targetStatus" class="form-control form-control-sm" required maxlength="50"
                    placeholder="Status" value="{{ old('targetStatus') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Reason</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" placeholder="Reason"
                    value="{{ old('reason') }}">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-sm btn-outline-primary" type="submit">Change status</button>
            </div>
        </form>
    </div>
@endsection
