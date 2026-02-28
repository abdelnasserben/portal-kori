@extends('layouts.app')

@section('content')
    <div class="card panel p-4">
        <h5 class="fw-semibold mb-2">Create an agent</h5>
        <div class="text-muted mb-3">
            A field operator who performs cash-in and cash-out transactions for clients.
        </div>

        <form method="POST" action="{{ route('admin.agents.store') }}" class="row g-3">
            @csrf
            <div class="col-12 col-md-6">
                <x-form.input name="displayName" label="Name" maxlength="120" required />
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.agents.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
