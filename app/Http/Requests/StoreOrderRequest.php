<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'delivery_type' => [
                'required',
                Rule::in(Order::$types),
            ],
            'delivery_time' => [
                'date_format:Y-m-d H:i:s',
                'required_if:delivery_type,' . Order::TYPE_DELIVERY,
                'after_or_equal:today',
            ],
            'address_id' => [
                'integer',
                'required_if:delivery_type,' . Order::TYPE_DELIVERY,
                'exists:addresses,id',
            ],
            'phone_id' => 'integer|exists:phones,id',
            'items' => 'array|required',
            'items.*.id' => 'integer|exists:items,id',
            'items.*.quantity' => 'integer|required',
        ];
    }
}