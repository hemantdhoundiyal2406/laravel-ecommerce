<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactAdminMail;
use App\Mail\ContactThanksMail;
use App\Models\ContactQuery;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $query = ContactQuery::create($data);

        try {
            Mail::to(Setting::getValue('admin_email', config('mail.from.address')))->send(new ContactAdminMail($query));
            Mail::to($query->email)->send(new ContactThanksMail($query));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return back()->with('success', 'Your message has been sent. We will contact you soon.');
    }
}
