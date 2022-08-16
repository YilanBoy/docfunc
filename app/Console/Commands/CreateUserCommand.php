<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
    {name? : The name of the user}
    {email? : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('name');
        $email = $this->argument('email') ?? $this->ask('email');

        if (User::whereEmail($email)->exists()) {
            $this->info('This email has been used');

            return self::INVALID;
        }

        User::forceCreate([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('Password101'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('User created, default password is "Password101"');

        return self::SUCCESS;
    }
}
