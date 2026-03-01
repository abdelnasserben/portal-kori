@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card panel p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Account profile management</h5>
        </div>

        <p class="text-muted mb-5" style="font-size:.9rem;">Perform update status action on account</p>

        <form method="POST" action="{{ route('admin.account-profiles.status.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-3">
                <x-form.select name="accountType" label="Account type" :options="$accountTypeOptions"
                    placeholder="Select an account type" required class="form-select form-select-sm" />
            </div>
            <div class="col-12 col-md-2">
                <x-form.input name="ownerRef" label="Owner" placeholder="Reference code" required
                    maxlength="120" autocomplete="off" class="form-control form-control-sm" />
            </div>
            <div class="col-6 col-md-2">
                <x-form.select name="targetStatus" label="New status" :options="$targetStatusOptions" placeholder="Select a status"
                    required class="form-select form-select-sm" />
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label mb-1">Reason</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" placeholder="Reason"
                    value="{{ old('reason') }}">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-sm btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
@endsection
