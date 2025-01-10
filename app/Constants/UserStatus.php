<?php

namespace App\Constants;

class UserStatus
{
    const BANNED = 0;
    const ACTIVE = 1;


    const STATUSES = [
        self::ACTIVE,
        self::BANNED,
    ];

}
