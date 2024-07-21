<?php

namespace App\Http\Controllers;

use app\Events\User\UserCreated;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->userService->createUser($data);

            event(new UserCreated($user));
            return response()->json([
                'data' => fractal($user, new UserTransformer())->toArray()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}