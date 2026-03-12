<?php 

namespace App\Dto\Accounts;

use App\Http\Requests\Accounts\UpdateAccountRequest;

readonly class UpdateAccountDto {

    public function __construct(
        public string $title
    ) {}

    public static function fromRequest(UpdateAccountRequest $request): self {
        return new self(
            title: $request->validated('title')
        );
    }

}