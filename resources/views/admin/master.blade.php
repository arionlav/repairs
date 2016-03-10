<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="{{ (isset($noIndex)) ? 'noindex, nofollow' : 'index, follow' }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/handler.js"></script>

    {{--<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>--}}

    <link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/style.css" type="text/css" />
    <link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/media.css" type="text/css" />

    <link rel="apple-touch-icon" sizes="57x57" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('var.pathToRoot') }}/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="{{ config('var.pathToRoot') }}/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('var.pathToRoot') }}/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ config('var.pathToRoot') }}/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('var.pathToRoot') }}/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ config('var.pathToRoot') }}/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ config('var.pathToRoot') }}/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    @yield('header')
</head>
<body>
@section('sidebar')
        <!--noindex-->
<div class="buttonMenu" id="buttonMenu"></div>
<a href="{{ url('/') }}" rel="nofollow">
    <div class="logoContainer"></div>
</a>
<!--/noindex-->
<div class="menuColumn clear" id="sideMenu">
    <div class="verticalMenu clear">
        <div class="hideMenu">СКРЫТЬ МЕНЮ</div>
        <div class="bgHouse"></div>
        @include('layouts.menu')
    </div>
    <!--noindex-->
    <div class="menuBottom"><a href='{{ url('info') }}' id="advLink" rel='nofollow'>Реклама на портале</a></div>
    <!--/noindex-->
</div>
<div class="opacityBgBlack"></div>
@show
<div class="generalContainer">
    <div class="generalColumn">
        <!--noindex-->
        @include('layouts.topLine')
                <!--/noindex-->
        <div class="content clear">
            <div class="postsContainer admin">
                @yield('content')
            </div>
        </div>
        @include('layouts.footer')
    </div>
</div>
<div id="navUp">
    <img src="{{ config('var.pathToRoot') }}/resources/img/to_top.png" /><p>ВВЕРХ</p>
</div>
</body>
</html>
