<?php

namespace app\Listeners\Order;

use app\Events\Order\OrderCanceled;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCanceledListener implements ShouldQueue
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
    public function handle(OrderCanceled $event): void
    {
        $order = $event->order;
        $this->mailService::sendOrderCanceledMail($order);
    }
}
