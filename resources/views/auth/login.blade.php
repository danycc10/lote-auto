@if (config('demo.enabled'))
    @include('auth.login-demo')
@else
    @include('auth.login-professional')
@endif
