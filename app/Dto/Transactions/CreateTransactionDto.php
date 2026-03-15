<?php 

namespace App\Dto\Transactions;

use App\Http\Requests\Transactions\StoreTransactionRequest;

readonly class CreateTransactionDto {

    public function __construct(
        public string $title,
        public float $value, 
        public string $reference,
        public int $account_id,
        public int $user_id
    ) {}

    public static function fromRequest(StoreTransactionRequest $request): self {
        return new self(
            title: $request->validated('title'),
            value: $request->validated('value'),
            reference: $request->validated('reference'),
            account_id: $request->validated('account_id'),
            user_id: auth()->id()
        );
    }

}