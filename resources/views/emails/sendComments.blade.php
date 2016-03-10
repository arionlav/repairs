<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
</head>
<body>
<h3>Здравствуйте!</h3>

<p>Вы получили ответ на Ваш комментарий на сайте <a href="{{ $main = url('/') }}">remont-mega.ru</a><br>
Чтобы прочитать его, перейдите по ссылке:</p>

<p><a href="{{ $url = URL::to('post' . $idPost . '/comments#comment' . $id) }}">{{ $url }}</a></p>

<p>Хорошего Вам дня! <br>
С уважением и любовью, команда <a href="{{ $main }}">remont-mega.ru</a></p>
</body>
</html>