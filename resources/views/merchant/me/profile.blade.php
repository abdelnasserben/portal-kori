@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Profile</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.home') }}">Back</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                <tr>
                    <th>Code</th>
                    <td class="mono">{{ $item['code'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $item['phone'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></td>
                </tr>
                <tr>
                    <th>Created at</th>
                    <td>@dateIso($item['createdAt'] ?? null, '—')</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
