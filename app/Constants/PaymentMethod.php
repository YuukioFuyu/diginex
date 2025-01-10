<?php

namespace App\Constants;

class PaymentMethod
{
    public const AUTOMATIC = 'auto';

    public const MANUAL = 'manual';

    public const TYPE = [
        self::AUTOMATIC,
        self::MANUAL,
    ];

}
