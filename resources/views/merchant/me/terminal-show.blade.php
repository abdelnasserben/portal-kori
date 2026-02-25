@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Détail terminal marchand</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.me.terminals') }}">Retour liste</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                @foreach (['terminalUid', 'status', 'createdAt', 'lastSeen', 'merchantCode'] as $field)
                    <tr>
                        <th style="width:220px;">{{ $field }}</th>
                        <td class="{{ in_array($field, ['terminalUid', 'merchantCode']) ? 'mono' : '' }}">
                            {{ $item[$field] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
