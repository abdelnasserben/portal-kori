@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Mon solde marchand</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.home') }}">Retour</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                <tr>
                    <th>Type de compte</th>
                    <td>{{ $item['accountType'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Owner ref</th>
                    <td class="mono">{{ $item['ownerRef'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Solde</th>
                    <td class="mono">{{ $item['balance'] ?? '—' }} {{ $item['currency'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
