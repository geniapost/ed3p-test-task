<?php

namespace app\Mail\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build(): Mailable
    {
        return $this->view('emails.order_canceled')->with(['order' => $this->order]);
    }
}
