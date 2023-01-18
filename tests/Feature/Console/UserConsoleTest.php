<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

it('can create new users', function () {
    $this->artisan('user:create')
        ->expectsQuestion('name', 'John')
        ->expectsQuestion('email', 'john@email.com')
        ->expectsOutput('User created, default password is "Password101"')
        ->assertExitCode(0);
});

it('will fail, if the email is already in use', function () {
    Artisan::call('user:create', [
        'name' => 'John',
        'email' => 'john@email.com',
    ]);

    $this->artisan('user:create')
        ->expectsQuestion('name', 'John')
        ->expectsQuestion('email', 'john@email.com')
        ->expectsOutput('This email has been used')
        ->assertExitCode(1);
});
