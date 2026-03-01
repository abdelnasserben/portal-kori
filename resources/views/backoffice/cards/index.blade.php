@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card panel p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Card Managment</h5>
        </div>

        <p class="text-muted mb-0" style="font-size:.9rem;">Perform operational actions on cards (status changes, unblock).
        </p>
    </div>

    <div class="card panel p-4 mb-3">
        <h6 class="fw-semibold mb-3">Change card status</h6>

        <form method="POST" action="{{ route('admin.cards.status.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-4">
                <x-form.input name="cardUid" label="Card UID" placeholder="Card UID" required
                    class="form-control form-control-sm" />
            </div>
            <div class="col-6 col-md-3">
                <x-form.select name="targetStatus" label="New status" :options="[
                    'ACTIVE' => 'ACTIVE',
                    'LOST' => 'LOST',
                    'SUSPENDED' => 'SUSPENDED',
                    'INACTIVE' => 'INACTIVE',
                ]" placeholder="Select a status"
                    required class="form-select form-select-sm" />
            </div>
            <div class="col-6 col-md-3">
                <x-form.input name="reason" label="Reason" placeholder="Reason" class="form-control form-control-sm" />
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-sm btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>

    <div class="card panel p-4">
        <h6 class="fw-semibold mb-3">Unblock a card</h6>

        <form method="POST" action="{{ route('admin.cards.unblock') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-12 col-md-4">
                <x-form.input name="cardUid" label="Card UID" placeholder="Card UID" required
                    class="form-control form-control-sm" />
            </div>
            <div class="col-6 col-md-6">
                <x-form.input name="reason" label="Reason" placeholder="Why are we unblocking this card?"
                    class="form-control form-control-sm" />
            </div>
            <div class="col-6 col-md-2 d-grid">
                <button class="btn btn-sm btn-warning" type="submit">Unblock</button>
            </div>
        </form>
    </div>
@endsection
