<?php

arch()->preset()->laravel();

arch()->preset()->security();

arch('app')
    ->expect('App\Livewire\Pages')
    ->toHaveSuffix('Page');
