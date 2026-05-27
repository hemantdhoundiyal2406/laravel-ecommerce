@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
    <div class="admin-card p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Website name</label><input name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required></div>
                <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="site_email" class="form-control" value="{{ old('site_email', $settings['site_email'] ?? '') }}" required></div>
                <div class="col-md-4"><label class="form-label">Admin email</label><input type="email" name="admin_email" class="form-control" value="{{ old('admin_email', $settings['admin_email'] ?? '') }}" required></div>
                <div class="col-md-4"><label class="form-label">Phone</label><input name="site_phone" class="form-control" value="{{ old('site_phone', $settings['site_phone'] ?? '') }}"></div>
                <div class="col-md-8"><label class="form-label">Address</label><input name="site_address" class="form-control" value="{{ old('site_address', $settings['site_address'] ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Tax/GST %</label><input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate', $settings['tax_rate'] ?? 18) }}" required></div>
                <div class="col-md-4"><label class="form-label">Shipping charge</label><input type="number" step="0.01" name="shipping_charge" class="form-control" value="{{ old('shipping_charge', $settings['shipping_charge'] ?? 79) }}" required></div>
                <div class="col-md-4"><label class="form-label">Free shipping minimum</label><input type="number" step="0.01" name="free_shipping_minimum" class="form-control" value="{{ old('free_shipping_minimum', $settings['free_shipping_minimum'] ?? 2999) }}" required></div>
                <div class="col-12"><label class="form-label">Footer content</label><textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">X URL</label><input type="url" name="x_url" class="form-control" value="{{ old('x_url', $settings['x_url'] ?? '') }}"></div>
                <div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
                <div class="col-md-6"><label class="form-label">Favicon</label><input type="file" name="favicon" class="form-control" accept="image/*"></div>
            </div>
            <hr>
            <h2 class="h5">SMTP reference settings</h2>
            <p class="text-muted small">These fields document SMTP values in database. Actual Laravel mailer reads from .env for security.</p>
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">SMTP host</label><input name="smtp_host" class="form-control" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"></div>
                <div class="col-md-3"><label class="form-label">SMTP port</label><input name="smtp_port" class="form-control" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}"></div>
                <div class="col-md-3"><label class="form-label">SMTP username</label><input name="smtp_username" class="form-control" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"></div>
                <div class="col-md-3"><label class="form-label">Encryption</label><input name="smtp_encryption" class="form-control" value="{{ old('smtp_encryption', $settings['smtp_encryption'] ?? 'tls') }}"></div>
            </div>
            <hr>
            <h2 class="h5">SEO meta for pages</h2>
            <p class="text-muted small">Use these fields to control browser title, meta description, meta keywords, Open Graph, and Twitter preview text for public pages.</p>
            <div class="accordion" id="seoSettings">
                @foreach(\App\Models\Setting::seoFields() as $key => $label)
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#seo{{ $loop->index }}">{{ $label }}</button>
                        </h3>
                        <div id="seo{{ $loop->index }}" class="accordion-collapse collapse @if($loop->first) show @endif" data-bs-parent="#seoSettings">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label">Meta title</label><input name="{{ $key }}_title" class="form-control" value="{{ old($key.'_title', $settings[$key.'_title'] ?? '') }}" maxlength="190"></div>
                                    <div class="col-md-6"><label class="form-label">Meta keywords</label><input name="{{ $key }}_keywords" class="form-control" value="{{ old($key.'_keywords', $settings[$key.'_keywords'] ?? '') }}" maxlength="500"></div>
                                    <div class="col-12"><label class="form-label">Meta description</label><textarea name="{{ $key }}_description" class="form-control" rows="3" maxlength="500">{{ old($key.'_description', $settings[$key.'_description'] ?? '') }}</textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="btn btn-success mt-4">Save Settings</button>
        </form>
    </div>
@endsection
