<?
use \App\Http\Models\GeneralModel;
?>
@extends('master', ['noIndex' => 1])

@section('title', 'Редактировать профиль')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Редактировать профиль</h2>
    <div class="lineDark lineDark90"></div>
    <form method="post" id="account" action="{{ url('account/update') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div id="textField" class="account">
            <label for="name" class="largeLabel accountModify">Ваше имя:</label>
            <input type="text" data="Имя" name="name" id="name" class="largeInput accountModify" value="{{
                    $user->name }}">
        </div>
        @if ($errors->has('name'))
            @include('auth.errorValidation', ['errors' => $errors->get('name'), 'class' => 'errorRegister'])
        @endif

        <div id="textField" class="account">
            <label for="city" class="largeLabel accountModify">Город:</label>
            <input type="text" data="Имя" name="city" id="city" class="largeInput accountModify" value="{{
                    $user->city }}">
        </div>
        @if ($errors->has('city'))
            @include('auth.errorValidation', ['errors' => $errors->get('city'), 'class' => 'errorRegister'])
        @endif

        <div id="textField" class="textAreaField account clear">
            <label for="interest" class="largeLabel accountModify">Интересы:</label>
            <textarea name="interest" id="interest"
                      placeholder="Например: Дизайн интерьера, строительство">{{$user->interest }}</textarea>
        </div>
        @if ($errors->has('interest'))
            @include('auth.errorValidation', ['errors' => $errors->get('interest'), 'class' => 'errorRegister'])
        @endif

        <div id="textField" class="textAreaField account clear">
            <label for="aboutMe" class="largeLabel accountModify">О себе:</label>
            <textarea name="aboutMe" id="aboutMe">{{ $user->about_me }}</textarea>
        </div>
        @if ($errors->has('aboutMe'))
            @include('auth.errorValidation', ['errors' => $errors->get('aboutMe'), 'class' => 'errorRegister'])
        @endif

        <div class="lineDark lineDark90"></div>

        <div class="withRadio account clear">
            <p>Пол:</p>
            <div><input type="radio" name="pol" id="hidePol" value="0"{{ ($user->pol == 0) ? 'checked' : '' }}>
            <label for="hidePol">Не указывать</label></div>
            <div><input type="radio" name="pol" id="male" value="1"{{ ($user->pol == 1) ? 'checked' : '' }}>
            <label for="male">Мужской</label></div>
            <div><input type="radio" name="pol" id="female" value="2"{{ ($user->pol == 2) ? 'checked' : '' }}>
            <label for="female">Женский</label></div>
        </div>

        <div class="withRadio account clear">
            <p class="pForSelect">Дата рождения:</p>
            <select name="bornDay" id="bornDay">
                <option value="---">---</option>
@for ($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}"{{
                    ($i == date('d', $timeBornUnix = strtotime($user->born)))
                        ? 'selected' : '' }}>
                    {{ $i }}
                </option>
@endfor
            </select>

            <select name="bornMonth" id="bornMonth">
                <option value="---">-----</option>
@foreach (GeneralModel::getAllMonth() as $monthNum => $monthName)
                <option value="{{ $monthNum }}"{{ ($monthNum == date('m', $timeBornUnix)) ? 'selected' : '' }}>
                    {{ $monthName }}
                </option>
@endforeach
            </select>

            <select name="bornYear" id="bornYear">
                <option value="---">---</option>
@for ($i = date('Y'); $i >= 1950; $i--)
                <option value="{{ $i }}"{{ ($i == date('Y', $timeBornUnix)) ? 'selected' : '' }}>{{ $i }}</option>
@endfor
            </select>
        </div>

        <div class="accountCheckboxSimple">
            <input type="checkbox" name="acceptPrivateMail"
                   id="acceptPrivateMail" {{ $user->accept_private_mail ? 'checked' : '' }}>
            <label for="acceptPrivateMail">Получать личные сообщения на почту</label>
        </div>

        <div class="accountCheckboxSimple">
            <input type="checkbox" name="acceptCommentsMail"
                   id="acceptCommentsMail" {{ $user->accept_comments_mail ? 'checked' : '' }}>
            <label for="acceptCommentsMail">Оповещать о новых комментариях по почте</label>
        </div>

        <div class="accountCheckboxSimple">
            <input type="checkbox" name="acceptRssMail"
                   id="acceptRssMail" {{ $user->accept_rss_mail ? 'checked' : '' }}>
            <label for="acceptRssMail">Получать интересные статьи по почте</label>
        </div>

        <div class="lineDark lineDark90"></div>

        <div id="textField" class="accountAvatar">
            <label class="largeLabel accountModify">Фотография</label>
            <div class="avatar accountModify">
                {!! (!is_file(base_path() . '/resources/users/' . $user->id . '.jpg'))
                        ? '<div><img src="' . config('var.pathToRoot') . '/resources/users/'
                                . 'default.jpg" alt=""></div>'
                        : '<div><img src="' . config('var.pathToRoot') . '/resources/users/' . $user->id
                                . '.jpg" alt=""></div>' !!}
            </div>
        </div>

        <div class="inputFile accountFile">
            <label for="file">Заменить фото</label>
            <input type="file" name="file" id="file" accept="image/jpeg,image/png,image/bmp">
        </div>

        <div class="accountCheckboxSimple">
            <input type="checkbox" name="deleteAvatar" id="deleteAvatar">
            <label for="deleteAvatar">Удалить фото</label>
        </div>
        @if ($errors->has('file'))
            @include('auth.errorValidation', ['errors' => $errors->get('file'), 'class' => 'errorRegister'])
        @endif

        <div class="lineDark lineDark90"></div>

        <button type="submit" id="button" class="accountButton">
            <div class="real_button">
                <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_accept.png"/>
                <p class="registrationButtonText">ВСЕ ВЕРНО</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>
@endsection
