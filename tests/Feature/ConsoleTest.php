<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_command()
    {
        $this->artisan('user:create')
            ->expectsQuestion('name', 'John')
            ->expectsQuestion('email', 'john@email.com')
            ->expectsOutput('User created, default password is "Password101"')
            ->assertExitCode(0);
    }

    public function test_create_user_command_will_fail_if_the_email_is_already_in_use()
    {
        Artisan::call('user:create', [
            'name' => 'John',
            'email' => 'john@email.com',
        ]);

        $this->artisan('user:create')
            ->expectsQuestion('name', 'John')
            ->expectsQuestion('email', 'john@email.com')
            ->expectsOutput('This email has been used')
            ->assertExitCode(2);
    }
}
