<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return ['sum' => 'required|numeric'];
    }
}