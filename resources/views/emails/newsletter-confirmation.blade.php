@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">Subscription confirmed</h1>
    <p>{{ $subscriber->email }} is now subscribed to UrbanCart offers, product launches, and updates.</p>
@endsection
