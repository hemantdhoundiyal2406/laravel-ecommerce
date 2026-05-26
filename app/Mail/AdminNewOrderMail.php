<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;

class AdminNewOrderMail extends Mailable
{
    public function __construct(public Order $order)
    {
    }

    public function build()
    {
        return $this->subject('New order received '.$this->order->order_number)->view('emails.admin-new-order');
    }
}
