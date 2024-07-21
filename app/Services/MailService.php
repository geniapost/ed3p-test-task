<?php

namespace App\Services;

use app\Mail\Order\OrderCanceledMail;
use app\Mail\Order\OrderCreatedMail;
use app\Mail\Order\OrderPaidMail;
use app\Mail\User\UserCreatedMail;
use App\Models\Order;
use app\Models\User\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public static function sendUserCreatedMail(User $user): void
    {
        Mail::to($user->email)->queue(new UserCreatedMail($user));
    }

    public static function sendOrderCreatedMail(Order $order): void
    {
        Mail::to($order->user->email)->queue(new OrderCreatedMail($order));
    }

    public static function sendOrderPaidMail(Order $order): void
    {
        Mail::to($order->user->email)->queue(new OrderPaidMail($order));
    }

    public static function sendOrderCanceledMail(Order $order): void
    {
        Mail::to($order->user->email)->queue(new OrderCanceledMail($order));
    }
}