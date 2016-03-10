@if (! isset($registerSuccess)) {
    {{ abort(404) }}
@endif

@extends('master', ['noIndex' => 1])

@section('title', 'Подтвердите Ваш email')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="verifyContent">
    <h2>ПОЗДРАВЛЯЕМ!</h2>
    <div class="lineDark"></div>
    <p>Регистрация почти закончена!</p>
    <p>Пожалуйста, проверьте Вашу почту и следуйте инструкции для завершения регистрации.</p>
    <p>Хорошего Вам дня!</p>
    <div class="lineDark"></div>
    <div><a href="{{ url('/') }}">На главную</a></div>
</div>
@endsection