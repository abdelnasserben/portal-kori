@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-1">Configuration plateforme</h5>
        <div class="text-muted" style="font-size:.9rem;">Frais, commissions et paramètres globaux</div>
    </div>

    <div class="card p-4 mb-3">
        <h6 class="fw-semibold mb-3">Frais</h6>
        <form method="POST" action="{{ route('admin.config.fees.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'cardEnrollmentPrice' => 'Prix enrôlement carte',
                    'cardPaymentFeeRate' => 'Taux frais paiement carte',
                    'cardPaymentFeeMin' => 'Min frais paiement carte',
                    'cardPaymentFeeMax' => 'Max frais paiement carte',
                    'merchantWithdrawFeeRate' => 'Taux frais retrait marchand',
                    'merchantWithdrawFeeMin' => 'Min frais retrait marchand',
                    'merchantWithdrawFeeMax' => 'Max frais retrait marchand',
                    'clientTransferFeeRate' => 'Taux frais transfert client',
                    'clientTransferFeeMin' => 'Min frais transfert client',
                    'clientTransferFeeMax' => 'Max frais transfert client',
                    'merchantTransferFeeRate' => 'Taux frais transfert marchand',
                    'merchantTransferFeeMin' => 'Min frais transfert marchand',
                    'merchantTransferFeeMax' => 'Max frais transfert marchand',
                ] as $field => $label)
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">{{ $label }}</label>
                        <input type="number" step="0.0001" name="{{ $field }}" class="form-control form-control-sm"
                            value="{{ old($field, $fees[$field] ?? '') }}" required>
                    </div>
                @endforeach
            </div>

            <div class="row g-2 mt-2">
                <div class="col-12 col-md-4">
                    <input type="hidden" name="cardPaymentFeeRefundable" value="0">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="cardPaymentFeeRefundable" value="1"
                            @checked((bool) old('cardPaymentFeeRefundable', $fees['cardPaymentFeeRefundable'] ?? false))>
                        <label class="form-check-label">Frais paiement carte remboursables</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <input type="hidden" name="merchantWithdrawFeeRefundable" value="0">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="merchantWithdrawFeeRefundable" value="1"
                            @checked((bool) old('merchantWithdrawFeeRefundable', $fees['merchantWithdrawFeeRefundable'] ?? false))>
                        <label class="form-check-label">Frais retrait marchand remboursables</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <input type="hidden" name="cardEnrollmentPriceRefundable" value="0">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="cardEnrollmentPriceRefundable" value="1"
                            @checked((bool) old('cardEnrollmentPriceRefundable', $fees['cardEnrollmentPriceRefundable'] ?? false))>
                        <label class="form-check-label">Prix enrôlement remboursable</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Enregistrer les frais</button>
            </div>
        </form>
    </div>

    <div class="card p-4 mb-3">
        <h6 class="fw-semibold mb-3">Commissions</h6>
        <form method="POST" action="{{ route('admin.config.commissions.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'cardEnrollmentAgentCommission' => 'Commission agent enrôlement',
                    'merchantWithdrawCommissionRate' => 'Taux commission retrait marchand',
                    'merchantWithdrawCommissionMin' => 'Min commission retrait marchand',
                    'merchantWithdrawCommissionMax' => 'Max commission retrait marchand',
                ] as $field => $label)
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">{{ $label }}</label>
                        <input type="number" step="0.0001" name="{{ $field }}" class="form-control form-control-sm"
                            value="{{ old($field, $commissions[$field] ?? '') }}"
                            @required(in_array($field, ['cardEnrollmentAgentCommission', 'merchantWithdrawCommissionRate'], true))>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Enregistrer les commissions</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-3">Plateforme</h6>
        <form method="POST" action="{{ route('admin.config.platform.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'agentCashLimitGlobal' => 'Limite cash globale agent',
                    'clientTransferMinPerTransaction' => 'Transfert client min / transaction',
                    'clientTransferMaxPerTransaction' => 'Transfert client max / transaction',
                    'clientTransferDailyMax' => 'Transfert client max / jour',
                    'merchantTransferMinPerTransaction' => 'Transfert marchand min / transaction',
                    'merchantTransferMaxPerTransaction' => 'Transfert marchand max / transaction',
                    'merchantTransferDailyMax' => 'Transfert marchand max / jour',
                    'merchantWithdrawMinPerTransaction' => 'Retrait marchand min / transaction',
                ] as $field => $label)
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">{{ $label }}</label>
                        <input type="number" step="0.0001" name="{{ $field }}" class="form-control form-control-sm"
                            value="{{ old($field, $platform[$field] ?? '') }}" required>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <label class="form-label mb-1">Raison (optionnel)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Enregistrer la plateforme</button>
            </div>
        </form>
    </div>
@endsection
