<?php

namespace App\Http\Controllers;

use app\Events\Order\OrderCanceled;
use app\Events\Order\OrderCreated;
use app\Events\Order\OrderPaid;
use App\Http\Requests\PayOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use app\Models\User\User;
use App\Services\OrderService;
use App\Transformers\OrderTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function store(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            $order = $this->orderService->createOrder($data);

            event(new OrderCreated($order));
            return response()->json([
                'data' => fractal($order, new OrderTransformer())->toArray()
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'unknown',
            ]);
        }
    }

    public function pay(Order $order, PayOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->orderService->setOrder($order)->pay($request->get('sum'));

            event(new OrderPaid($order));
            return response()->json([
                'data' => fractal($order, new OrderTransformer())->toArray()
            ]);
        } catch (\LogicException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'unknown',
            ]);
        }
    }

    public function cancel(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            $this->orderService->setOrder($order)->cancelOrder();

            event(new OrderCanceled($order));
            return response()->json([
                'data' => fractal($order, new OrderTransformer())->toArray()
            ]);
        } catch (\LogicException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'unknown',
            ]);
        }
    }

    public function index(User $user)
    {
        try {
            if ($user) {
                return response()->json([
                    'data' => fractal()
                        ->collection($user->orders, new OrderTransformer())
                        ->toArray()
                ]);
            } else {
                throw new NotFoundHttpException('User not found');
            }
        } catch (\LogicException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'unknown',
            ]);
        }
    }

    public function get(Order $order): \Illuminate\Http\JsonResponse
    {
        try {
            if ($order) {
                return response()->json([
                    'data' => fractal($order, new OrderTransformer())->toArray()
                ]);
            } else {
                throw new NotFoundHttpException('Order not found');
            }
        }  catch (\LogicException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'unknown',
            ]);
        }
    }
}