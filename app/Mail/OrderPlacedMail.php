<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;

class OrderPlacedMail extends Mailable
{
    public function __construct(public Order $order)
    {
    }

    public function build()
    {
        return $this->subject('Order confirmation '.$this->order->order_number)->view('emails.order-placed');
    }
}
