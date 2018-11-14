@if($user != null && $mailMessage != null)
@component('mail::message')

# Hello, {{$user->name}}!

{{ $mailMessage }}

Your login is {{ $user->email }}<br>

@if($password != null)
Your password is <b>{{ $password }}</b>
@endif

@endcomponent
@else
@component('mail::message')

# Introduction

default mail message

Sanks, user!

@endcomponent

@endif
