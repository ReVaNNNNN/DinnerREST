<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Witaj,
<br>
<br>
    Dziękujemy za stworzenie konta w serwisie wybierzobiad.pl, nie zapomnij dokończyć rejestracji!
<br>
    Aby dokończyć rejestrację kliknij w poniższy link:
<br>
<br>

<a href="{{ url('user/verify', $token)}}">Potwierdź adres e-mail</a>

<br>
<br>
    Zespół wybierzobiad.pl
</div>

</body>
</html>