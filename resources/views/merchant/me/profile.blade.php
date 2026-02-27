@extends('layouts.app')

@section('content')
    <div class="card panel p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Profile</h5>
            <a class="btn btn-sm btn-outline-dark" href="{{ route('merchant.home') }}">Back</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                <tr>
                    <th>Code</th>
                    <td class="mono">{{ $item['code'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $item['displayName'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><x-status-badge :value="$item['status'] ?? ''" /></td>
                </tr>
                <tr>
                    <th>Created at</th>
                    <td>@dateIso($item['createdAt'] ?? null, '—')</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
