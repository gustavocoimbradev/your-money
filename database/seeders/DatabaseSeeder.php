<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@example.com',
        ]);
         User::factory()->create([
            'name' => 'User Test',
            'email' => 'user2@example.com',
        ]);
        // $account1 = Account::factory()->create([
        //     'title' => 'Wallet',
        //     'balance' => -100.00,
        //     'user_id' => $user
        // ]); 
        // $account2 = Account::factory()->create([
        //     'title' => 'Bank',
        //     'balance' => 250.00,
        //     'user_id' => $user
        // ]); 
        
        // Transaction::factory(10)->create([
        //     'user_id' => $user,
        //     'account_id' => $account1
        // ]);

        // Transaction::factory(10)->create([
        //     'user_id' => $user,
        //     'account_id' => $account2
        // ]);

    }
}
