@extends('master', ['noIndex' => 1])

@section('title', 'Размещение рекламы')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Размещение рекламы</h2>
    <div class="lineDark"></div>
    <div class="fullText center">
        <p>По вопросам размещения рекламы на потрале в любом виде,<br>
        будь то баннер, информационная статья или брендированный фон</p>
        <img src="resources/img/logo_big.png" alt="" class="logoInfo">
        <p>обращайтесь по E-mail: <strong>business{{ '@' }}remont-mega.ru</strong></p>
    </div>
</div>
@endsection