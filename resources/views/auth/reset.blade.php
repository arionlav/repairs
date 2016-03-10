@extends('master', ['noIndex' => 1])

@section('title', 'Изменение пароля')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Изменение пароля</h2>
    <div class="lineDark"></div>

    <form method="post" id="login" action="{{ url('password/reset') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        <div id="textField">
            <label for="email" class="largeLabel">Ваш Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
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
                <p class="registrationButtonText">ПРИМЕНИТЬ ПАРОЛЬ</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>
@endsection