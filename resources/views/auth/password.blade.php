@extends('master', ['noIndex' => 1])

@section('title', 'Восстановление пароля')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Восстановление пароля</h2>

    <div class="lineDark"></div>

    <form method="POST" id="login" action="{{ url('password/email') }}">
        {!! csrf_field() !!}
        <div id="textField">
            <label for="email" class="largeLabel">Ваш Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
        </div>
        @if ($errors->has('email'))
            @include('auth.errorValidation', ['errors' => $errors->get('email'), 'class' => 'errorRegister'])
        @endif

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

        <div>
            <button type="submit" id="button" class="registrationButton">
                <div class="real_button">
                    <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_send_mail.png"/>
                    <p style="margin-left: -10px;">ОТПРАВИТЬ ПИСЬМО</p>
                    <div class="overlayHoverButtonRed"></div>
                </div>
            </button>
        </div>
    </form>
</div>
@endsection