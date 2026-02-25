@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Détail transaction marchand</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.transactions') }}">Retour liste</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                @foreach (['transactionRef', 'type', 'status', 'amount', 'fee', 'totalDebited', 'currency', 'agentCode', 'clientCode', 'originalTransactionRef', 'createdAt'] as $field)
                    <tr>
                        <th style="width:220px;">{{ $field }}</th>
                        <td class="{{ str_contains($field, 'Ref') || str_contains($field, 'Code') ? 'mono' : '' }}">
                            {{ $item[$field] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
