<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use Illuminate\Http\Request;

class ContactQueryController extends Controller
{
    public function index(Request $request)
    {
        $queries = ContactQuery::when($request->get('read') === '0', fn ($q) => $q->where('is_read', false))
            ->when($request->get('read') === '1', fn ($q) => $q->where('is_read', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contacts.index', compact('queries'));
    }

    public function show(ContactQuery $contact)
    {
        $contact->update(['is_read' => true]);

        return view('admin.contacts.show', ['query' => $contact]);
    }

    public function update(Request $request, ContactQuery $contact)
    {
        $contact->update($request->validate([
            'is_read' => ['nullable', 'boolean'],
            'is_replied' => ['nullable', 'boolean'],
        ]));

        return back()->with('success', 'Contact query updated.');
    }

    public function destroy(ContactQuery $contact)
    {
        $contact->delete();

        return back()->with('success', 'Contact query deleted.');
    }
}
