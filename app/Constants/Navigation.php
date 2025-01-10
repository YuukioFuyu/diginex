<?php

namespace App\Constants;

class Navigation
{
    public const HEADER = 'header';

    public const FOOTER = 'footer';

    public const BOTH = 'both';

    public const DISPLAY = [
        self::HEADER,
        self::FOOTER,
        self::BOTH,
    ];

    public const TARGET = [
        'Self' => '_self',
        'Blank' => '_blank',
    ];
}
