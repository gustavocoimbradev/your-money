<?php

namespace App\Dto\Accounts;

use App\Http\Requests\Accounts\StoreAccountRequest;

readonly class CreateAccountDto {

    public function __construct(
        public string $title,
        public int $user_id
    ) {}

    public static function fromRequest(StoreAccountRequest $request): self {
        return new self(
            title: $request->validated('title'),
            user_id: auth()->user()->id
        );
    }

}