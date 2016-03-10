<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
</head>
<body>
<h3>Здравствуйте!</h3>
<p>Для Вашего E-mail был запрос на изменение пароля на сайте <a href="{{ $main = url('/') }}">remont-mega.ru</a></p>

<p>Для изменения пароля, перейдите по ссылке и введите новый пароль: <br>
<a href="{{ $tokenLink = url('password/reset/' . $token) }}">{{ $tokenLink }}</a></p>

<p>Хорошего Вам дня! <br>
С уважением и любовью, команда <a href="{{ $main }}">remont-mega.ru</a></p>
</body>
</html>