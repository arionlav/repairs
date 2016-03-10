@if (! isset($resettingSuccess)) {
    {{ abort(404) }}
@endif

@extends('master', ['noIndex' => 1])

@section('title', 'ПОЗДРАВЛЯЕМ')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="verifyContent">
    <h2>ПОЗДРАВЛЯЕМ!</h2>
    <div class="lineDark"></div>
    <p>Пароль почти восстановлен!</p>
    <p>Пожалуйста, проверьте Вашу почту и следуйте инструкции для изменения пароля.</p>
    <p>Хорошего Вам дня!</p>
    <div class="lineDark"></div>
    <div><a href="{{ url('/') }}">На главную</a></div>
</div>
@endsection