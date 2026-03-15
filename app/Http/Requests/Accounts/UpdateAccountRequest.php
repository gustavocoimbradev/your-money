<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
                'max:255',
                Rule::unique('accounts', 'title')
                    ->ignore($this->route('account'))
                    ->where(function($query) {
                        return $query->where('user_id', auth()->id());
                    })
            ]
        ];
    }
}
