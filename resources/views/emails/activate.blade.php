@component('mail::message')
# ALMOST THERE!

The next step in activating your new account on ARES DIGITAL 3.0 is to select a password.  You can do this by clicking on the button below.

@component('mail::button', ['url' => url('auth/activate/' . $token . '/' . $tokenType)])
    Create Password
@endcomponent

This reset link will expire in 720 minutes.

If you did not request an account activation on ARES DIGITAL 3.0, no further action is required.

Regards,<br>
The Axanar Team
@endcomponent
