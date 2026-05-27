@extends('layouts.frontend')

@section('title', \App\Models\Setting::getValue('seo_login_title', 'Login - UrbanCart'))
@section('meta_description', \App\Models\Setting::getValue('seo_login_description', 'Login to your UrbanCart customer account.'))
@section('meta_keywords', \App\Models\Setting::getValue('seo_login_keywords', 'UrbanCart login, customer account'))

@section('content')
    <section class="container py-5">
        <div class="mini-panel p-4 mx-auto" style="max-width: 480px">
            <h1 class="h3 mb-3">Login</h1>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-check"><input type="checkbox" name="remember" class="form-check-input"> <span class="form-check-label">Remember me</span></label>
                    <a href="{{ route('password.request') }}">Forgot?</a>
                </div>
                <button class="btn btn-brand w-100" type="submit">Login</button>
            </form>
            <p class="small text-muted mt-3 mb-0">Demo: admin@example.com / password, customer@example.com / password</p>
            <p class="mt-3 mb-0">New here? <a href="{{ route('register') }}">Create account</a></p>
        </div>
    </section>
@endsection
