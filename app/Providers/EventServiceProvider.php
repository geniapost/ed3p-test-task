<?php

namespace App\Providers;

use app\Events\Order\OrderCanceled;
use app\Events\Order\OrderCreated;
use app\Events\Order\OrderPaid;
use app\Events\User\UserCreated;
use app\Listeners\Order\OrderCanceledListener;
use app\Listeners\Order\OrderCreatedListener;
use app\Listeners\Order\OrderPaidListener;
use app\Listeners\User\UserCreatedListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            UserCreatedListener::class,
        ],
        OrderCreated::class => [
            OrderCreatedListener::class,
        ],
        OrderPaid::class => [
            OrderPaidListener::class,
        ],
        OrderCanceled::class => [
            OrderCanceledListener::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
