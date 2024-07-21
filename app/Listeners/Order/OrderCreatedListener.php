<?php

namespace app\Listeners\Order;

use app\Events\Order\OrderCreated;
use App\Jobs\ProcessOrder;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreatedListener implements ShouldQueue
{
    protected MailService $mailService;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->mailService = new MailService();
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        dispatch(new ProcessOrder($order));
        $this->mailService::sendOrderCreatedMail($order);
    }
}
