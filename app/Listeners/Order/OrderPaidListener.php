<?php

namespace app\Listeners\Order;

use app\Events\Order\OrderPaid;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidListener implements ShouldQueue
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
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        $this->mailService::sendOrderPaidMail($order);
    }
}
