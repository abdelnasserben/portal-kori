@extends('layouts.app')

@section('content')
    <div class="card panel p-4">
        <h5 class="fw-semibold mb-2">Create a merchant</h5>
        <div class="text-muted mb-3">
            A business that accepts payments through platform for goods or services.
        </div>

        <form method="POST" action="{{ route('admin.merchants.store') }}">
            @csrf
            <div class="col-12 col-md-6">
                <x-form.input name="displayName" label="Name" :required="true" maxlength="120" />
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.merchants.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
