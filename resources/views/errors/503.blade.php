<!DOCTYPE html>
<html>
<head>
    <title>503</title>

    <link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/style.css" type="text/css" />
    <script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/jquery-2.1.4.min.js"></script>

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 22px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <p class="title">Извините, Произошла ошибка</p>
        <p class="timeLeft" style="font-size: 16px;">Вы бедете автоматически переадрессованы на Главную страницу через</p>
        <p><span id="timeLeft" style="color: #B0BEC5; font-size: 72px;">8</span></p>
        <p>секунд</p>
    </div>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/redirectAfter.js"></script>
</body>
</html>
