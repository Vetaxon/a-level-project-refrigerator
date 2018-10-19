
<html>
<head></head>
<body style="background: black; color: white">
<h1>Hello{{$user->name}}</h1>
<a href="{{$user->email}}">Ссылка на мыло</a>
<p>Data is: {{ $mailMessage }} </p>
</body>
</html>
