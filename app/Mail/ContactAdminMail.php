<?php

namespace App\Mail;

use App\Models\ContactQuery;
use Illuminate\Mail\Mailable;

class ContactAdminMail extends Mailable
{
    public function __construct(public ContactQuery $query)
    {
    }

    public function build()
    {
        return $this->subject('New contact query: '.$this->query->subject)->view('emails.contact-admin');
    }
}
