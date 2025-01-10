<?php

namespace App\Constants;

class NotificationType
{
    const DEPOSIT_USER = 'deposit_user';
    const DEPOSIT_ACTION_USER = 'deposit_action_user';
    const DEPOSIT_ADMIN = 'deposit_admin';
    const DEPOSIT_ACTION_ADMIN = 'deposit_action_admin';
    const SERVICE_ACTION_USER = 'service_action_user';
    const SERVICE_USER = 'service_user';
    const SERVICE_ADMIN = 'service_admin';
    const SERVICE_ACTION_ADMIN = 'service_action_admin';
    const SUBSCRIPTION_USER = 'subscription_user';
    const SUBSCRIPTION_ACTION_USER = 'subscription_action_user';
    const SUBSCRIPTION_ADMIN = 'subscription_admin';
    const SUBSCRIPTION_ACTION_ADMIN = 'subscription_action_admin';
    const BALANCE_UPDATED_USER = 'balance_updated_user';
    const SUPPORT_USER = 'support_user';
    const SUPPORT_ADMIN = 'support_admin';

    public const TYPE = [
        self::DEPOSIT_USER,
        self::DEPOSIT_ACTION_USER,
        self::DEPOSIT_ADMIN,
        self::DEPOSIT_ACTION_ADMIN,
        self::SERVICE_ACTION_USER,
        self::SERVICE_USER,
        self::SERVICE_ADMIN,
        self::SERVICE_ACTION_ADMIN,
        self::SUBSCRIPTION_USER,
        self::SUBSCRIPTION_ACTION_USER,
        self::SUBSCRIPTION_ADMIN,
        self::SUBSCRIPTION_ACTION_ADMIN,
        self::BALANCE_UPDATED_USER,
        self::SUPPORT_USER,
        self::SUPPORT_ADMIN,
    ];

    public const ICON = [
        TxnType::ADD_BALANCE => 'wallet-plus',
        TxnType::SUBTRACT_BALANCE => 'wallet-minus',
        TxnType::DEPOSIT => 'deposit',
        TxnType::SERVICE_PURCHASE => 'service',
        TxnType::SUBSCRIPTION => 'subscription',
        type::SUPPORT_TICKET => 'ticket',
        type::NOTIFICATION => 'info',
    ];
}
