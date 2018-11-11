<html>
<head></head>
<body style="background: black; color: white">
<h3>Hello {{$user->name}}</h3>
<p>{{ $mailMessage }}</p>

@if($password != null)
<p>Your login is <b>{{ $user->email }}</b></p>
<p>Your password is <b>{{ $password }}</b></p>
@endif
</body>
</html>
