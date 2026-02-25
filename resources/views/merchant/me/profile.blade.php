@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-semibold mb-0">Mon profil marchand</h5>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('merchant.home') }}">Retour</a>
        </div>

        <table class="table table-sm mb-0 align-middle">
            <tbody>
                <tr>
                    <th>Code</th>
                    <td class="mono">{{ $item['code'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td>{{ $item['phone'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td><span class="badge text-bg-light">{{ $item['status'] ?? '—' }}</span></td>
                </tr>
                <tr>
                    <th>Créé le</th>
                    <td>{{ $item['createdAt'] ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
