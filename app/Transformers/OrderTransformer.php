<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order): array
    {
        $result = [
            'id' => $order->id,
            'delivery_type' => Order::$typesLabels[$order->delivery_type],
            'delivery_time' => $order->delivery_time->format('Y-m-d H:i'),
            'status' => Order::$statusLabels[$order->status],
            'user_id' => $order->user_id,
            'total_price' => $order->total_price,
        ];

        foreach ($order->items as $item) {
            $result['items'][] = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
            ];
        }

        if ($order->phone) {
            $result['phone'] = [
                'id' => $order->phone->id,
                'phone' => $order->phone->phone,
            ];
        }

        if ($order->address) {
            $result['address'] = [
                'id' => $order->address->id,
                'address' => $order->address->address,
            ];
        }

        return $result;
    }
}