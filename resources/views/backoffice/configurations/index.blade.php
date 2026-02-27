@extends('layouts.app')

@section('content')
    @if (session('status_success'))
        <div class="alert alert-success">{{ session('status_success') }}</div>
    @endif

    <div class="card panel p-4 mb-3">
        <h5 class="fw-semibold mb-1">Platform configuration</h5>
        <div class="text-muted" style="font-size:.9rem;">Fees, commissions, and global settings</div>
    </div>

    <div class="card panel p-4 mb-3">
        <h6 class="fw-semibold mb-4">Fees</h6>

        <form method="POST" action="{{ route('admin.config.fees.update') }}">
            @csrf

            {{-- CARD PAYMENT --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Card Payment</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="cardPaymentFeeRate" label="Rate (%)" type="number" step="0.0001"
                            :value="old('cardPaymentFeeRate', $fees['cardPaymentFeeRate'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="cardPaymentFeeMin" label="Min" type="number" step="0.0001"
                            :value="old('cardPaymentFeeMin', $fees['cardPaymentFeeMin'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="cardPaymentFeeMax" label="Max" type="number" step="0.0001"
                            :value="old('cardPaymentFeeMax', $fees['cardPaymentFeeMax'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- MERCHANT WITHDRAW --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Merchant Withdraw</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="merchantWithdrawFeeRate" label="Rate (%)" type="number" step="0.0001"
                            :value="old('merchantWithdrawFeeRate', $fees['merchantWithdrawFeeRate'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="merchantWithdrawFeeMin" label="Min" type="number" step="0.0001"
                            :value="old('merchantWithdrawFeeMin', $fees['merchantWithdrawFeeMin'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="merchantWithdrawFeeMax" label="Max" type="number" step="0.0001"
                            :value="old('merchantWithdrawFeeMax', $fees['merchantWithdrawFeeMax'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- ENROLLMENT --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Card Enrollment</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="cardEnrollmentPrice" label="Price" type="number" step="0.0001"
                            :value="old('cardEnrollmentPrice', $fees['cardEnrollmentPrice'] ?? '')" required class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- REFUNDABLE --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Refund Rules</div>
                <div class="row g-3">
                    <div class="col-md-4 form-check">
                        <input type="hidden" name="cardPaymentFeeRefundable" value="0">
                        <input class="form-check-input" type="checkbox" name="cardPaymentFeeRefundable" value="1"
                            @checked((bool) old('cardPaymentFeeRefundable', $fees['cardPaymentFeeRefundable'] ?? false))>
                        <label class="form-check-label small">Card payment refundable</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="hidden" name="merchantWithdrawFeeRefundable" value="0">
                        <input class="form-check-input" type="checkbox" name="merchantWithdrawFeeRefundable" value="1"
                            @checked((bool) old('merchantWithdrawFeeRefundable', $fees['merchantWithdrawFeeRefundable'] ?? false))>
                        <label class="form-check-label small">Withdraw refundable</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="hidden" name="cardEnrollmentPriceRefundable" value="0">
                        <input class="form-check-input" type="checkbox" name="cardEnrollmentPriceRefundable" value="1"
                            @checked((bool) old('cardEnrollmentPriceRefundable', $fees['cardEnrollmentPriceRefundable'] ?? false))>
                        <label class="form-check-label small">Enrollment refundable</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-form.input name="reason" label="Reason" placeholder="Reason" class="form-control form-control-sm" />
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary">Save Fees</button>
            </div>
        </form>
    </div>

    <div class="card panel p-4 mb-3">
        <h6 class="fw-semibold mb-4">Commissions</h6>

        <form method="POST" action="{{ route('admin.config.commissions.update') }}">
            @csrf

            {{-- ENROLLMENT --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Enrollment</div>
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <x-form.input name="cardEnrollmentAgentCommission" label="Agent commission" type="number"
                            step="0.0001" required :value="old(
                                'cardEnrollmentAgentCommission',
                                $commissions['cardEnrollmentAgentCommission'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- MERCHANT WITHDRAW --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Merchant Withdraw</div>
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <x-form.input name="merchantWithdrawCommissionRate" label="Rate (%)" type="number"
                            step="0.0001" required :value="old(
                                'merchantWithdrawCommissionRate',
                                $commissions['merchantWithdrawCommissionRate'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>

                    <div class="col-12 col-md-3">
                        <x-form.input name="merchantWithdrawCommissionMin" label="Min" type="number" step="0.0001"
                            :value="old(
                                'merchantWithdrawCommissionMin',
                                $commissions['merchantWithdrawCommissionMin'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>

                    <div class="col-12 col-md-3">
                        <x-form.input name="merchantWithdrawCommissionMax" label="Max" type="number" step="0.0001"
                            :value="old(
                                'merchantWithdrawCommissionMax',
                                $commissions['merchantWithdrawCommissionMax'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-form.input name="reason" label="Reason" placeholder="Reason" maxlength="255" :value="old('reason')"
                    class="form-control form-control-sm" />
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>

    <div class="card panel p-4">
        <h6 class="fw-semibold mb-4">Limits</h6>

        <form method="POST" action="{{ route('admin.config.platform.update') }}">
            @csrf

            {{-- AGENT --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Agent</div>
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <x-form.input name="agentCashLimitGlobal" label="Global Cash Limit" type="number"
                            step="0.0001" required :value="old('agentCashLimitGlobal', $platform['agentCashLimitGlobal'] ?? '')" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- CLIENT TRANSFER --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Client Transfer</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="clientTransferMinPerTransaction" label="Min / Tx" type="number"
                            step="0.0001" required :value="old(
                                'clientTransferMinPerTransaction',
                                $platform['clientTransferMinPerTransaction'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="clientTransferMaxPerTransaction" label="Max / Tx" type="number"
                            step="0.0001" required :value="old(
                                'clientTransferMaxPerTransaction',
                                $platform['clientTransferMaxPerTransaction'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="clientTransferDailyMax" label="Daily Max" type="number" step="0.0001"
                            required :value="old('clientTransferDailyMax', $platform['clientTransferDailyMax'] ?? '')" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- MERCHANT TRANSFER --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Merchant Transfer</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="merchantTransferMinPerTransaction" label="Min / Tx" type="number"
                            step="0.0001" required :value="old(
                                'merchantTransferMinPerTransaction',
                                $platform['merchantTransferMinPerTransaction'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="merchantTransferMaxPerTransaction" label="Max / Tx" type="number"
                            step="0.0001" required :value="old(
                                'merchantTransferMaxPerTransaction',
                                $platform['merchantTransferMaxPerTransaction'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input name="merchantTransferDailyMax" label="Daily Max" type="number" step="0.0001"
                            required :value="old('merchantTransferDailyMax', $platform['merchantTransferDailyMax'] ?? '')" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            {{-- MERCHANT WITHDRAW --}}
            <div class="mb-4">
                <div class="fw-semibold small text-muted mb-2">Merchant Withdraw</div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-form.input name="merchantWithdrawMinPerTransaction" label="Min / Tx" type="number"
                            step="0.0001" required :value="old(
                                'merchantWithdrawMinPerTransaction',
                                $platform['merchantWithdrawMinPerTransaction'] ?? '',
                            )" class="form-control form-control-sm" />
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <x-form.input name="reason" label="Reason" placeholder="Reason" maxlength="255"
                    :value="old('reason')" class="form-control form-control-sm" />
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-sm btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
