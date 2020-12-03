<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'password' => bcrypt('1qaz2wsx'),
            'introduction' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
