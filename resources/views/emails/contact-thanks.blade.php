@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">Thanks, {{ $query->name }}</h1>
    <p>We received your message about <strong>{{ $query->subject }}</strong>. Our team will reply soon.</p>
@endsection
