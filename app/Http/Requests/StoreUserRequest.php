<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'email|required|unique:emails,email',
            'phone' => 'string|nullable|unique:phones,phone',
            'address' => 'string|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Ця адреса вже використовується',
            'phone.unique' => 'Цей номер телефону вже використовується',
        ];
    }
}