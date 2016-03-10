@extends('master', ['noIndex' => 1])

@section('title', 'Отправить сообщение')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="messagesContainer clear">
    <h2>Отправить сообщение</h2>
    <div class="lineDark lineDark90"></div>
    <div class="commentBody sendMessageField forUser">
        <p>Введите текст сообщения для пользователя: <strong>"{{ $user->name }}"</strong> ниже</p>
        <form action="{{ url('account/messages-for-user') }}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="forUser" value="{{ $user->id }}">
            <input type="hidden" name="fromUser" value="{{ Auth::user()->id }}">
            <textarea name="text" id="text" placeholder="Текст Вашего сообщения"></textarea>
            <input type="submit" id="button" value="Отправить">
        </form>
    </div>
</div>
@endsection
