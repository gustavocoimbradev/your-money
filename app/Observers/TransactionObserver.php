<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->paid) {
            $transaction->account->increment('current_balance', $transaction->value);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        if($transaction->wasChanged('value')) {
            $diference = $transaction->value - $transaction->getOriginal('value');
            $transaction->account->increment('current_balance', $diference);
        } else {
            if ($transaction->paid) {
                $transaction->account->increment('current_balance', $transaction->value);
            } else {
                $transaction->account->decrement('current_balance', $transaction->value);
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        if ($transaction->paid) {
            $transaction->account->decrement('current_balance', $transaction->value);
        }
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        if ($transaction->paid) {
            $transaction->account->incremet('current_balance', $transaction->value);
        }
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
