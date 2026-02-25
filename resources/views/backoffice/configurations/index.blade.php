@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card p-4 mb-3">
        <h5 class="fw-semibold mb-1">Platform configuration</h5>
        <div class="text-muted" style="font-size:.9rem;">Fees, commissions, and global settings</div>
    </div>

    <div class="card p-4 mb-3">
        <h6 class="fw-semibold mb-3">Frais</h6>
        <form method="POST" action="{{ route('admin.config.fees.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'cardEnrollmentPrice' => 'Card enrollment price',
                    'cardPaymentFeeRate' => 'Card payment fee rate',
                    'cardPaymentFeeMin' => 'Card payment fee min',
                    'cardPaymentFeeMax' => 'Card payment fee max',
                    'merchantWithdrawFeeRate' => 'Merchant withdrawal fee rate',
                    'merchantWithdrawFeeMin' => 'Merchant withdrawal fee min',
                    'merchantWithdrawFeeMax' => 'Merchant withdrawal fee max',
                    'clientTransferFeeRate' => 'Client transfer fee rate',
                    'clientTransferFeeMin' => 'Client transfer fee min',
                    'clientTransferFeeMax' => 'Client transfer fee max',
                    'merchantTransferFeeRate' => 'Merchant transfer fee rate',
                    'merchantTransferFeeMin' => 'Merchant transfer fee min',
                    'merchantTransferFeeMax' => 'Merchant transfer fee max',
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
                        <label class="form-check-label">Refundable card payment fees</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <input type="hidden" name="merchantWithdrawFeeRefundable" value="0">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="merchantWithdrawFeeRefundable" value="1"
                            @checked((bool) old('merchantWithdrawFeeRefundable', $fees['merchantWithdrawFeeRefundable'] ?? false))>
                        <label class="form-check-label">Refundable merchant withdrawal fees</label>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <input type="hidden" name="cardEnrollmentPriceRefundable" value="0">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="cardEnrollmentPriceRefundable" value="1"
                            @checked((bool) old('cardEnrollmentPriceRefundable', $fees['cardEnrollmentPriceRefundable'] ?? false))>
                        <label class="form-check-label">Refundable enrollment price</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label mb-1">Reason (optional)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Save fees</button>
            </div>
        </form>
    </div>

    <div class="card p-4 mb-3">
        <h6 class="fw-semibold mb-3">Commissions</h6>
        <form method="POST" action="{{ route('admin.config.commissions.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'cardEnrollmentAgentCommission' => 'Agent enrollment commission',
                    'merchantWithdrawCommissionRate' => 'Merchant withdrawal commission rate',
                    'merchantWithdrawCommissionMin' => 'Merchant withdrawal commission min',
                    'merchantWithdrawCommissionMax' => 'Merchant withdrawal commission max',
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
                <label class="form-label mb-1">Reason (optional)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Save commissions</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h6 class="fw-semibold mb-3">Platform</h6>
        <form method="POST" action="{{ route('admin.config.platform.update') }}">
            @csrf
            <div class="row g-2">
                @foreach ([
                    'agentCashLimitGlobal' => 'Agent global cash limit',
                    'clientTransferMinPerTransaction' => 'Client transfer min / transaction',
                    'clientTransferMaxPerTransaction' => 'Client transfer max / transaction',
                    'clientTransferDailyMax' => 'Client transfer max / day',
                    'merchantTransferMinPerTransaction' => 'Merchant transfer min / transaction',
                    'merchantTransferMaxPerTransaction' => 'Merchant transfer max / transaction',
                    'merchantTransferDailyMax' => 'Merchant transfer max / day',
                    'merchantWithdrawMinPerTransaction' => 'Merchant withdrawal min / transaction',
                ] as $field => $label)
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">{{ $label }}</label>
                        <input type="number" step="0.0001" name="{{ $field }}" class="form-control form-control-sm"
                            value="{{ old($field, $platform[$field] ?? '') }}" required>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <label class="form-label mb-1">Reason (optional)</label>
                <input name="reason" class="form-control form-control-sm" maxlength="255" value="{{ old('reason') }}">
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Save platform</button>
            </div>
        </form>
    </div>
@endsection
