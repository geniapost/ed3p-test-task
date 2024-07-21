<?php

namespace App\Transformers;

use app\Models\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $result = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        foreach ($user->phones as $phone) {
            $result['phones'][] = [
                'id' => $phone->id,
                'phone' => $phone->phone,
            ];
        }

        foreach ($user->addresses as $address) {
            $result['addresses'][] = [
                'id' => $address->id,
                'address' => $address->address,
            ];
        }

        return $result;
    }
}