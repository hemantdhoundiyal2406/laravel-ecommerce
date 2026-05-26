<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterConfirmationMail;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:190']]);

        $subscriber = Newsletter::updateOrCreate(
            ['email' => $data['email']],
            ['is_active' => true, 'subscribed_at' => now()]
        );

        try {
            Mail::to($subscriber->email)->send(new NewsletterConfirmationMail($subscriber));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return back()->with('success', 'Newsletter subscription confirmed.');
    }
}
