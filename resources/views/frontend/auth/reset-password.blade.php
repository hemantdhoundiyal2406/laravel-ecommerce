@extends('layouts.frontend')

@section('title', 'Reset Password - UrbanCart')

@section('content')
    <section class="container py-5">
        <div class="mini-panel p-4 mx-auto" style="max-width: 480px">
            <h1 class="h3 mb-3">Reset password</h1>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control mb-3" value="{{ old('email', $email) }}" required>
                <label class="form-label">New password</label>
                <input type="password" name="password" class="form-control mb-3" required>
                <label class="form-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="form-control mb-3" required>
                <button class="btn btn-brand w-100" type="submit">Reset password</button>
            </form>
        </div>
    </section>
@endsection
