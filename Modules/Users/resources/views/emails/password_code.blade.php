@component('mail::message')
    {{ __('users::auth.reset_password_mail') }}
    <br>
    {{ __('users::auth.welecome') }} **{{ $name }}**
    <br>
    {{ __('users::auth.your_reset_password_code_is') }} **{{ $code }}**
    <br>
    {{ __('users::auth.please_use_this_code_to_reset_your_password') }}
    <br>
    {{ __('users::auth.thank_you_for_using_our_application') }}
    <br>
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. {{ __('users::auth.all_rights_reserved') }}
    @endcomponent
@endcomponent
