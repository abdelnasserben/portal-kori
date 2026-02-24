<?php

namespace App\Support\Backoffice;

class FilterEnums
{
    public const ACTOR_TYPES = ['AGENT', 'CLIENT', 'MERCHANT', 'TERMINAL', 'ADMIN'];

    public const ACTOR_STATUSES = ['ACTIVE', 'SUSPENDED', 'CLOSED'];

    public const TRANSACTION_TYPES = [
        'ENROLL_CARD',
        'PAY_BY_CARD',
        'MERCHANT_WITHDRAW_AT_AGENT',
        'AGENT_PAYOUT',
        'AGENT_BANK_DEPOSIT_RECEIPT',
        'REVERSAL',
        'CASH_IN_BY_AGENT',
        'CLIENT_REFUND',
        'CLIENT_TRANSFER',
        'MERCHANT_TRANSFER',
    ];

    public const TRANSACTION_STATUSES = ['COMPLETED', 'REQUESTED', 'FAILED'];

    public const LEDGER_ACCOUNT_TYPES = [
        'CLIENT',
        'MERCHANT',
        'AGENT_WALLET',
        'AGENT_CASH_CLEARING',
        'PLATFORM_FEE_REVENUE',
        'PLATFORM_CLEARING',
        'PLATFORM_CLIENT_REFUND_CLEARING',
        'PLATFORM_BANK',
    ];

    public const TRANSACTION_HISTORY_VIEWS = ['SUMMARY', 'PAY_BY_CARD_VIEW', 'COMMISSION_VIEW'];

    public static function options(array $values): array
    {
        return array_combine($values, $values);
    }
}

