@if($user != null && $mailMessage != null && $password != null)
@component('mail::message')

# Hello, {{$user->name}}!

{{ $mailMessage }}

Your login is {{ $user->email }}<br>

Your password is <b>{{ $password }}</b>

@endcomponent
@else
@component('mail::message')

# Introduction

default mail message

Sanks, user!

@endcomponent

@endif
