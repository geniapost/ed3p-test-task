<?php

namespace App\Services;

use app\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected User $user;

    public function createUser(array $data): User
    {
        $user = null;

        DB::transaction(function () use (&$user, $data) {
            /**
             * @var User $user
             */
            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
            ]);

            if ($data['address']) {
                $user->addresses()->create([
                    'address' => $data['address'],
                ]);
            }

            if ($data['phone']) {
                $user->phones()->create([
                    'phone' => $data['phone'],
                ]);
            }
        });

        if ($user) {
            return $user;
        } else {
            throw new \Exception('User not created. Unknown error');
        }
    }
}