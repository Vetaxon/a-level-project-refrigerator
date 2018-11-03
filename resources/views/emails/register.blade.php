<html>
<head></head>
<body style="background: black; color: white">
<h3>Hello {{$user->name}}</h3>
<p>{{ $mailMessage }}</p>
<p>Your login is <b>{{ $user->email }}</b>, your password: <b>{{ $password }}</b></p>
</body>
</html>
