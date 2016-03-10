<?
use \App\Http\Models\GeneralModel;
?>
@extends('master', ['noIndex' => 1])

@section('title', 'Личный кабинет')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="bgWhiteContent">
    <div class="clear">
        <div class="avatar accountUser">
        {!! (!is_file(base_path() . '/resources/users/' . $user->id . '.jpg'))
                ? '<div><img src="' . config('var.pathToRoot') . '/resources/users/'
                        . 'default.jpg" alt=""></div>'
                : '<div><img src="' . config('var.pathToRoot') . '/resources/users/' . $user->id
                        . '.jpg" alt=""></div>' !!}
        </div>
        <h2>{{ $user->name }}</h2>
        <div class="accountIcons accountModifyIcon">
            <a href="{{ url('account/modify') }}" title="Редактировать профиль"></a>
        </div>
        <div class="accountIcons accountMailIcon">
            <a href="{{ url('account/messages') }}" title="Личные сообщения"></a>
        </div>
    </div>

    <div class="accountMenu clear">
        <span><a href="{{ url('account/messages') }}" id="accountMessages">Сообщения</a>
            {!! ($countMessages = Auth::user()->count_new_messages)
                    ? "<a title='Непрочитанные сообщения' href='" . url('account/messages')
                        . "' id='messages' rel='nofollow'>" . $countMessages . "</a>"
                    : ''
            !!}</span>
        <span><a href="{{ url('account/like-posts') }}">Избранное</a></span>
        <span><a href="{{ url('account/modify') }}">Настройки</a></span>
        <span><a href="{{ url('auth/logout') }}">Выйти</a></span>
    </div>

    <div class="field clear">
        <p><span class="largeLabel">Город: </span><span>{{ $user->city }}</span></p>
    </div>

    <div class="field clear">
        <p><span>Пол: </span><span>{{ ($user->pol == 0) ? 'Не указывать'
                : (($user->pol == 1) ? 'Мужской' : 'Женский') }}</span></p>
    </div>

    <div class="field clear">
        <p class="pForSelect"><span>Дата рождения: </span><span>{{
                GeneralModel::getRussianDate($user->born, true, true) }}</span></p>
    </div>

    <div class="field clear">
        <p><span class="largeLabel">Зарегистрирован: </span><span>{{
                GeneralModel::getRussianDate($user->created_at, true, true) }}</span></p>
    </div>

    <div class="lineDark lineDark100"></div>

    <div class="field clear">
        <p><span class="largeLabel">Интересы: </span><span>{{ $user->interest }}</span></p>
    </div>

    <div class="field clear">
        <p><span class="largeLabel">О себе: </span><span>{{ $user->about_me }}</span></p>
    </div>

    <div class="field clear">
        <p><span class="largeLabel">Статей прочтено: </span><span><strong>{{ $user->count_review }}</strong></span></p>
    </div>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>
@endsection
