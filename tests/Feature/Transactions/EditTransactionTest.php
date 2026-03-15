<?php

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;

test('transaction editing page can be displayed', function(){
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'user_id' => $user->id
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'account_id' => $account->id
    ]);
    $response = $this->actingAs($transaction->user)
        ->get(route('transactions.edit', $transaction));
    $response->assertOk();
});

test('transaction can be updated', function() {

    $user = User::factory()->create();
    $account = Account::factory()->create([
        'user_id' => $user->id
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'account_id' => $account->id
    ]);
    $payload = [
        'title' => 'New title',
        'value' => 2.00,
        'reference' => '2026-03-12',
        'account_id' => $account->id
    ];
    $response = $this->actingAs($transaction->user)
        ->put(route('transactions.update', $transaction), $payload);
    $response->assertSessionHasNoErrors();
    $this->AssertDatabaseHas('transactions', $payload);
});