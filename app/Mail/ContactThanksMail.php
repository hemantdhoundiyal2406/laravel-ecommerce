<?php

namespace App\Mail;

use App\Models\ContactQuery;
use Illuminate\Mail\Mailable;

class ContactThanksMail extends Mailable
{
    public function __construct(public ContactQuery $query)
    {
    }

    public function build()
    {
        return $this->subject('Thanks for contacting UrbanCart')->view('emails.contact-thanks');
    }
}
