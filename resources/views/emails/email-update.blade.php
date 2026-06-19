<x-mail::message>
# Confirm Your Email Change

Hi {{ $user->name }},

You requested to change your email address to **{{ $newEmail }}**.

Click the button below to confirm this change. This link will expire in **1 hour**.

<x-mail::button :url="$verificationUrl">
Confirm Email Change
</x-mail::button>

If you did not request this change, please ignore this email and your email will remain unchanged.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
