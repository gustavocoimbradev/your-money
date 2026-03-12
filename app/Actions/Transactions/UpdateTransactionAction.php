<?php 

namespace App\Actions\Transactions;

use App\Dto\Transactions\UpdateTransactionDto;
use App\Models\Transaction;

class UpdateTransactionAction {

    public function __invoke(UpdateTransactionDto $dto, Transaction $transaction) {
        return (bool) $transaction->update([
            'title' => $dto->title,
            'value' => $dto->value,
            'reference' => $dto->reference,
            'account_id' => $dto->account_id
        ]);
    } 

}