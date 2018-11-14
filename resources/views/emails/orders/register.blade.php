@component('mail::message')

@if($user != null)
# Hello, {{$user->name}}!

@else
# Hello, user!

@endif

@if($mailMessage != null)
{{ $mailMessage }}

@else
Default mail message

@endif

@if($password != null && $user != null)
Your login is {{ $user->email }}<br>
Your password is <b>{{ $password }}</b><br>

@endif

Thanks,
{{ config('app.name') }}

@endcomponent
