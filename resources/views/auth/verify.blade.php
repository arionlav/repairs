@if (! isset($verifyIsGood)) {
    {{ abort(404) }}
@endif

@extends('master', ['noIndex' => 1])

@section('title', 'Регистрация окончена')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="verifyContent">
    <h2>ПОЗДРАВЛЯЕМ!</h2>
    <div class="lineDark"></div>
    <p>Ваш E-mail подтвержден!</p>
    <p>Можете войти, используя свои регистрационные данные</p>
    <div class="lineDark"></div>
    <div class="verifyRedirect"><a href="{{ url('auth/login') }}">ВОЙТИ НА САЙТ</a></div>
    <p class="timeLeft">Вы бедете автоматически переадрессованы на страницу входа через
        <span id="timeLeft">10</span> секунд
    </p>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/redirectAfter.js"></script>
@endsection