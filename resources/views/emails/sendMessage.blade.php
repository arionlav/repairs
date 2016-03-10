<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
</head>
<body>
<h3>Здравствуйте!</h3>

<p>Вам пришло личное сообщение на сайте <a href="{{ $main = url('/') }}">remont-mega.ru</a><br>
Чтобы прочитать сообщение, для начала, авторизируйтесь на сайте, перейдя по ссылке:</p>

<p><a href="{{ $url = URL::to('auth/login') }}">{{ $url }}</a></p>

<p>А затем, войдите в <a href="{{ URL::to('account/messages') }}">Личный кабинет - Сообщения</a> или перейдите по ссылке ниже:</p>

<p><a href="{{ $url = URL::to('account/messages') }}">{{ $url }}</a></p>

<p>Хорошего Вам дня! <br>
С уважением и любовью, команда <a href="{{ $main }}">remont-mega.ru</a></p>
</body>
</html>