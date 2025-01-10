<?php

namespace App\Constants;

class OrderStatus
{
    public const PENDING = 'pending';

    public const WORKING = 'working';

    public const COMPLETED = 'completed';

    public const CANCELLED = 'cancelled';

    public const STATUS = [
        self::PENDING,
        self::WORKING,
        self::COMPLETED,
        self::CANCELLED,
    ];

}
