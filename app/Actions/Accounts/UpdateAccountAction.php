<?php 

namespace App\Actions\Accounts;

use App\Dto\Accounts\UpdateAccountDto;
use App\Models\Account;

class UpdateAccountAction {

    public function __invoke(UpdateAccountDto $dto, Account $account): bool {
        return (bool) $account->update([
            'title' => $dto->title
        ]);
    }

}