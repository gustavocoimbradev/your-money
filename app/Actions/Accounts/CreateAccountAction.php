<?php 

namespace App\Actions\Accounts;

use App\Dto\Accounts\CreateAccountDto;
use App\Models\Account;

class CreateAccountAction {

    public function __invoke(CreateAccountDto $dto): bool {
        return (bool) Account::create([
            'title' => $dto->title,
            'user_id' => $dto->user_id
        ]);
    }

}