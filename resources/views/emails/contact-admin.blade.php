@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">New contact query</h1>
    <p><strong>{{ $query->name }}</strong> sent a message.</p>
    <p>Email: {{ $query->email }}<br>Phone: {{ $query->phone ?: '-' }}<br>Subject: {{ $query->subject }}</p>
    <p>{{ $query->message }}</p>
@endsection
