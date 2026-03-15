<?php

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;

test('transaction can be soft deleted', function() {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'user_id' => $user->id
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'account_id' => $account->id
    ]);
    $response = $this->actingAs($transaction->user)
        ->delete(route('transactions.destroy', $transaction));
    $response->assertSessionHasNoErrors();
    $this->assertSoftDeleted($transaction);
});
