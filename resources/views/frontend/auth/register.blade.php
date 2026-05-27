@extends('layouts.frontend')

@section('title', \App\Models\Setting::getValue('seo_register_title', 'Register - UrbanCart'))
@section('meta_description', \App\Models\Setting::getValue('seo_register_description', 'Create your UrbanCart customer account for faster shopping and order tracking.'))
@section('meta_keywords', \App\Models\Setting::getValue('seo_register_keywords', 'UrbanCart register, create account'))

@section('content')
    <section class="container py-5">
        <div class="mini-panel p-4 mx-auto" style="max-width: 560px">
            <h1 class="h3 mb-3">Create account</h1>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Name</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" value="{{ old('phone') }}" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" required></div>
                    <div class="col-12"><button class="btn btn-brand w-100" type="submit">Register</button></div>
                </div>
            </form>
            <p class="mt-3 mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </section>
@endsection
