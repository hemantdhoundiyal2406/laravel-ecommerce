<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'UrbanCart' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f7f9; margin:0; padding:24px;">
<div style="max-width:640px; margin:auto; background:#fff; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
    <div style="background:#14532d; color:#fff; padding:20px 24px; font-size:22px; font-weight:bold;">UrbanCart</div>
    <div style="padding:24px; color:#111827;">
        @yield('body')
    </div>
    <div style="padding:16px 24px; font-size:12px; color:#6b7280; border-top:1px solid #e5e7eb;">This is an automated email from UrbanCart.</div>
</div>
</body>
</html>
