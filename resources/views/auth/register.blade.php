@extends('master', ['noIndex' => 1])

@section('title', 'Регистрация на сайте')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Регистрация на сайте</h2>
    <div class="lineDark"></div>
    <form method="POST" id="login" action="{{ url('auth/register') }}">
        {!! csrf_field() !!}
        <div id="textField">
            <label for="name" class="largeLabel">Ваше имя</label>
            <input type="text" data="Имя" name="name" id="name" value="{{ old('name') }}">
        </div>
        @if ($errors->has('name'))
            @include('auth.errorValidation', ['errors' => $errors->get('name'), 'class' => 'errorRegister'])
        @endif

        <div id="textField">
            <label for="email" class="largeLabel">Email</label>
            <input type="text" name="email" id="email" value="{{ old('email') }}">
        </div>
        @if ($errors->has('email'))
            @include('auth.errorValidation', ['errors' => $errors->get('email'), 'class' => 'errorRegister'])
        @endif

        <div id="textField">
            <label for="password" class="largeLabel">Пароль</label>
            <input type="password" name="password" id="password">
        </div>
        @if ($errors->has('password'))
            @include('auth.errorValidation', ['errors' => $errors->get('password'), 'class' => 'errorRegister'])
        @endif

        <div id="textField">
            <label for="password_confirmation" class="largeLabel">Повторите пароль</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

        <div class="captchaDiv">
            <div>{!! captcha_img('flat') !!}</div>
            <p class="captchaReloadText">Нажмите на картинку чтобы обновить</p>
        </div>
        <div id="textField">
            <label for="captcha" class="largestLabel captchaLabel">Введите символы<br> с картинки выше</label>
            <input type="text" name="captcha" id="captcha">
        </div>
        @if ($errors->has('captcha'))
            @include('auth.errorValidation', ['errors' => $errors->get('captcha'), 'class' => 'errorRegister'])
        @endif

        <button type="submit" id="button" class="registrationButton">
            <div class="real_button">
                <img src="{{ config('var.pathToRoot') }}/resources/img/real_button2.png"/>
                <p class="registrationButtonText">РЕГИСТРАЦИЯ</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>
@endsection