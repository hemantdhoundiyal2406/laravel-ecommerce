<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function show(string $page)
    {
        $pages = [
            'about-us' => 'About Us',
            'privacy-policy' => 'Privacy Policy',
            'terms-and-conditions' => 'Terms & Conditions',
            'refund-policy' => 'Refund Policy',
            'shipping-policy' => 'Shipping Policy',
            'faq' => 'FAQ',
        ];

        abort_unless(array_key_exists($page, $pages), 404);

        return view('frontend.static.show', [
            'slug' => $page,
            'title' => $pages[$page],
        ]);
    }
}
