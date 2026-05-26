@extends('layouts.frontend')

@section('title', 'Forgot Password - UrbanCart')

@section('content')
    <section class="container py-5">
        <div class="mini-panel p-4 mx-auto" style="max-width: 480px">
            <h1 class="h3 mb-3">Forgot password</h1>
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control mb-3" value="{{ old('email') }}" required>
                <button class="btn btn-brand w-100" type="submit">Send reset link</button>
            </form>
        </div>
    </section>
@endsection
