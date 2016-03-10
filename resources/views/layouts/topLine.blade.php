<?
use \App\Http\Models\GeneralModel;
?>
<div class="generalTopLine"></div>
<div class="generalTopLineContainer clear">
    <div class="centerColumn">
        <div class="generalTopLine generalTopLineImage"></div>
        <div class="loginLink">
        @if (Auth::check())
            {!! (!Gate::denies('isAdmin', config('var.lowestEnterRole')))
                    ? '<a href="' . url("admin") . '" id="adminPanel" rel="nofollow">Админпанель</a>'
                    : '' !!}
            <a href='{{ url('account') }}' id="userPage" rel='nofollow'>Личный кабинет</a>
            {!! ($countMessages = Auth::user()->count_new_messages)
                    ? "<a title='Непрочитанные сообщения' href='" . url('account/messages')
                        . "' id='messages' rel='nofollow'>" . $countMessages . "</a>"
                    : '' !!}
            <a href='{{ url('auth/logout') }}' id="logout" rel='nofollow'>Выйти</a>
        @else
            <a href='{{ url('auth/login') }}' id='login' rel='nofollow'>Войти</a>
            <a href='{{ url('auth/register') }}' id='registration' rel='nofollow'>Регистрация</a>
        @endif
        </div>
    </div>
    <a href="{{ url('info') }}">
        <div class="rightButton">
            <p>Размещение рекламы</p>
        </div>
    </a>
</div>
<div class="horizontalMenu">
    @include('layouts.menu', ['noFollow' => 1])
</div>
