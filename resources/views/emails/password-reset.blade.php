@component('mail::message')
# Password Reset Request

Hello {{ $userName }},

You have requested to reset your password for your Alymart account. Please click the button below to proceed:

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

**Important:** This password reset link will expire in {{ $expiresIn }} minutes.

If you did not request this password reset, please ignore this email or contact support immediately.

---

**Security Notice:**
- Never share this link with anyone
- Always use the link provided in this email to reset your password
- If you suspect account compromise, contact support immediately

Thanks,<br>
{{ config('app.name') }} Team

@slot('subcopy')
If you're having trouble clicking the "Reset Password" button, copy and paste this URL into your web browser:
[{{ $resetUrl }}]({{ $resetUrl }})
@endslot

@endcomponent
