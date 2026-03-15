<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest 
{

    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [ 
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'value' => [
                'required',
                'numeric'
            ],
            'reference' => [
                'required',
                'string'
            ],
            'account_id' => [
                'required',
                'numeric',
                'exists:accounts,id'
            ]
        ];
    }
}
