<?php

arch('do not use debug statements in production')
    ->expect(['dd', 'dump', 'var_dump'])
    ->not->toBeUsed();

arch('Service classes should be suffixed with "Services"')
    ->expect('App\Services')
    ->toHaveSuffix('Service');
