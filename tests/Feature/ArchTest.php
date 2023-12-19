<?php

arch('Not debugging statements are left in our code.')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

arch('All controllers should be suffixed with `Controller`.')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');
