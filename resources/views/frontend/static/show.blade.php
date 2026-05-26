@extends('layouts.frontend')

@section('title', $title.' - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="mini-panel p-4 p-lg-5">
            <span class="text-uppercase small text-muted fw-bold">Information</span>
            <h1 class="display-6 fw-bold mb-4">{{ $title }}</h1>

            @if ($slug === 'faq')
                <div class="accordion" id="faqAccordion">
                    @foreach ([
                        'How do I track my order?' => 'Login to your account dashboard and open My Orders. Each order shows status and tracking number when admin updates it.',
                        'Which payments are supported?' => 'Cash on Delivery is working. Online payment has a clean placeholder where Razorpay or Stripe keys can be connected.',
                        'How do returns work?' => 'Eligible products can be requested for return within the policy period after delivery.',
                    ] as $question => $answer)
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $loop->index }}">{{ $question }}</button></h2>
                            <div id="faq{{ $loop->index }}" class="accordion-collapse collapse @if($loop->first) show @endif" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ $answer }}</div></div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="lead text-muted">This {{ strtolower($title) }} page is part of the e-commerce content section and can be expanded from Blade templates or a CMS module.</p>
                <p>UrbanCart is built as a full Laravel commerce application with product catalog, filters, cart, checkout, coupons, orders, customer account, admin panel, SMTP emails, and role based access.</p>
                <p>Policy pages should be reviewed with actual business, tax, shipping, and legal requirements before production launch.</p>
            @endif
        </div>
    </section>
@endsection
