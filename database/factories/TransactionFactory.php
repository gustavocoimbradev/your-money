<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {   
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        return [
            'title' => fake()->word(),
            'value' => fake()->randomFloat(2, -100, 200),
            'reference' => fake()->date(),
            'account_id' => $account->id,
            'user_id' => $user->id
        ];
    }

}
