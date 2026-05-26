<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Mail\Mailable;

class NewsletterConfirmationMail extends Mailable
{
    public function __construct(public Newsletter $subscriber)
    {
    }

    public function build()
    {
        return $this->subject('Newsletter subscription confirmed')->view('emails.newsletter-confirmation');
    }
}
