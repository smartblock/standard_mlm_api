@component('mail::message')
# Verification code:

{{ $mailData['otp'] }}

The verification code will expire after 15 minutes. Do not share your code with anyone.

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

This is an automated message, please do not reply.
@endcomponent
