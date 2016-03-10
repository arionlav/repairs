<?
use \App\Http\Models\GeneralModel;
?>
@extends('master', ['noIndex' => 1])

@section('title', $user->name . ' - страница пользователя')
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
@if (Auth::check() and Auth::user()->id != $user->id)
        <div class="accountIcons accountMailIcon userMessageIcon">
            <a href="{{ url('account/messages-for-user/' . $user->id) }}" title="Отправить сообщение">Сообщение</a>
        </div>
@endif
    </div>

    <div class="lineDark lineDark100 firstLine"></div>

@if ($user->city)
    <div class="field clear">
        <p><span class="largeLabel">Город: </span><span>{{ $user->city }}</span></p>
    </div>
@endif
@if ($user->pol != 0)
    <div class="field clear">
        <p><span>Пол: </span><span>{{ ($user->pol == 1) ? 'Мужской' : 'Женский' }}</span></p>
    </div>
@endif
@if ($user->born)
    <div class="field clear">
        <p class="pForSelect"><span>Возраст: </span><span>{{ GeneralModel::getAge($user->born) }}</span></p>
    </div>
@endif
    <div class="field clear">
        <p><span class="largeLabel">Зарегистрирован: </span><span>{{
                GeneralModel::getRussianDate($user->created_at, true, true) }}</span></p>
    </div>

    <div class="lineDark lineDark100"></div>

@if ($user->interest)
    <div class="field clear">
        <p><span class="largeLabel">Интересы: </span><span>{{ $user->interest }}</span></p>
    </div>
@endif
@if ($user->about_me)
    <div class="field clear">
        <p><span class="largeLabel">О себе: </span><span>{{ $user->about_me }}</span></p>
    </div>
@endif

    <div class="field clear">
        <p><span class="largeLabel">Статей прочтено: </span><span><strong>{{ $user->count_review }}</strong></span></p>
    </div>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>
@endsection
