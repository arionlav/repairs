@extends('master', ['noIndex' => 1])

@section('title', 'Вход на сайт')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Вход на сайт</h2>
    <div class="lineDark"></div>

    <form method="POST" id="login" action="{{ config('var.pathToRoot') }}/auth/login">
        {!! csrf_field() !!}
        <div id="textField">
            <label for="email">Ваш Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
        </div>

        <div id="textField">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password">
        </div>
        @if (count($errors) > 0)
            @include('auth.errorValidation', ['errors' => $errors, 'class' => 'errorLogin'])
        @endif

        <div id="rememberMe">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Запомнить меня</label>
        </div>

        <div class="resetPasswordLink"><a href="{{ url('password/email') }}">Забыли пароль?</a></div>

        <button type="submit" id="button" class="login">
            <div class="real_button">
                <img src="{{ config('var.pathToRoot') }}/resources/img/real_button.png"/>
                <p>ВОЙТИ НА САЙТ</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>
@endsection