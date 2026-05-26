<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('admin.newsletters.index', ['subscribers' => Newsletter::latest()->paginate(20)]);
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return back()->with('success', 'Subscriber deleted.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Active', 'Subscribed At']);
            Newsletter::orderBy('email')->chunk(100, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [$subscriber->email, $subscriber->is_active ? 'Yes' : 'No', $subscriber->subscribed_at]);
                }
            });
            fclose($handle);
        }, 'newsletter-subscribers.csv');
    }
}
