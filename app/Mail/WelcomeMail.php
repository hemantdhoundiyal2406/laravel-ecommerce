<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class WelcomeMail extends Mailable
{
    public function __construct(public User $user)
    {
    }

    public function build()
    {
        return $this->subject('Welcome to UrbanCart')->view('emails.welcome');
    }
}
