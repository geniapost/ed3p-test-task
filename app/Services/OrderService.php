<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Order;
use app\Models\User\Address;
use app\Models\User\Phone;
use app\Models\User\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected Order $order;

    public function setOrder(Order $order): static
    {
        $this->order = $order;
        return $this;
    }

    public function createOrder(array $data): Order
    {
        /**
         * @var User $user
         */
        $user = User::find($data['user_id']);
        $order = null;

        DB::transaction(function () use ($user, $data, &$order) {
            /**
             * @var Order $order
             */
            foreach ($data['items'] as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                if ($itemData['quantity'] > $item->quantity) {
                    throw new \LogicException('Not enough ' . $item->name . ' in the stock.');
                }
            }

            $order = Order::create([
                'status' => Order::STATUS_CREATED,
                'delivery_type' => $data['delivery_type'],
                'delivery_time' => $data['delivery_time'],
            ]);

            $order->user()->associate($user);

            foreach ($data['items'] as $itemData) {
                $order->items()->attach($itemData['id'], ['quantity' => $itemData['quantity']]);
            }

            if ($data['address_id']) {
                $address = Address::find($data['address_id']);
                $order->address()->associate($address);
            }

            if ($data['phone_id']) {
                $phone = Phone::find($data['phone_id']);
                $order->phone()->associate($phone);
            }
        });

        if ($order) {
            return $order;
        } else {
            throw new \Exception('Order not created. Unknown error.');
        }
    }

    public function pay(float $sum): static
    {
        if (in_array($this->order->status, [Order::STATUS_PAID, Order::STATUS_CANCELLED, Order::STATUS_DELIVERED])) {
            throw new \LogicException('Order already paid or cancelled.');
        }

        if ($sum === $this->order->total_price) {
            $this->order->status = Order::STATUS_PAID;
            $this->order->save();
        } else {
            throw new \LogicException('Sum is incorrect');
        }

        return $this;
    }

    public function cancelOrder(): static
    {
        if (!$this->order->can_be_canceled) {
            throw new \LogicException('Order can not be canceled');
        }

        $this->order->status = Order::STATUS_CANCELLED;
        $this->order->save();

        return $this;
    }
}