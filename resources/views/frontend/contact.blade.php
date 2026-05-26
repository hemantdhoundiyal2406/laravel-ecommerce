@extends('layouts.frontend')

@section('title', 'Contact Us - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Support</span><h1 class="mb-0">Contact us</h1></div>
        </div>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="mini-panel p-4">
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
                            <div class="col-md-6"><label class="form-label">Subject</label><input name="subject" class="form-control" value="{{ old('subject') }}" required></div>
                            <div class="col-12"><label class="form-label">Message</label><textarea name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea></div>
                            <div class="col-12"><button class="btn btn-brand" type="submit">Send Message</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="mini-panel p-4 h-100">
                    <h2 class="h4">Company details</h2>
                    <div class="d-grid gap-3 mt-3">
                        <div><i class="fa-solid fa-envelope text-success me-2"></i>{{ \App\Models\Setting::getValue('site_email', 'support@urbancart.test') }}</div>
                        <div><i class="fa-solid fa-phone text-success me-2"></i>{{ \App\Models\Setting::getValue('site_phone', '+91 98765 43210') }}</div>
                        <div><i class="fa-solid fa-location-dot text-success me-2"></i>{{ \App\Models\Setting::getValue('site_address', 'Bengaluru, India') }}</div>
                    </div>
                    <div class="ratio ratio-16x9 mt-4 rounded overflow-hidden border">
                        <iframe src="https://maps.google.com/maps?q=Bengaluru&t=&z=12&ie=UTF8&iwloc=&output=embed" loading="lazy" title="Google Map"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
