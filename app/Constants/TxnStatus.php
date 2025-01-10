<?php

namespace App\Constants;

class TxnStatus
{
    public const PENDING = 'pending';

    public const COMPLETED = 'completed';

    public const FAILED = 'failed';

    public const STATUS = [
        self::PENDING,
        self::COMPLETED,
        self::FAILED,
    ];

}
