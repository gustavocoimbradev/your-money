<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'current_balance' => fake()->randomFloat(2),
            'user_id' => User::factory()->create()
        ];
    }

}
