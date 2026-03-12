<?php 

namespace App\Actions\Transactions;

use App\Dto\Transactions\CreateTransactionDto;
use App\Models\Account;
use App\Models\Transaction;

class CreateTransactionAction {

    public function __invoke(CreateTransactionDto $dto): bool {
        return (bool) Transaction::create([
            'title' => $dto->title,
            'value' => $dto->value,
            'reference' => $dto->reference,
            'account_id' => $dto->account_id,
            'user_id' => $dto->user_id
        ]);
    }

}