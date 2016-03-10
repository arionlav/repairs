<?
use \App\Http\Models\GeneralModel;
?>
@extends('master', ['noIndex' => 1])

@section('title', 'Сообщения')
@section('keywords', '')
@section('description', '')

@section('content')
@include('layouts.beauty')
<div class="messagesContainer privateMessage clear">
    <h2>Сообщения</h2>
    <div class="lineDark lineDark90"></div>
    <div class="messagesTable">
        <div class="rowLeft">
@foreach($usersOrder as $userOrder)
@foreach($users as $u)
@if($userOrder == $u->id)
            <a href="{{ url('account/messages/' . $u->id) }}">
                <div class="messageUser clear{{ ($fromUser == $u->id) ? ' selectUserLeft' : ''}}">
                    <div class="avatar messageBox">
                        {!! (!is_file(base_path() . '/resources/users/' . $u->id . '.jpg'))
                                ? '<div><img src="' . config('var.pathToRoot') . '/resources/users/'
                                        . 'default.jpg" alt=""></div>'
                                : '<div><img src="' . config('var.pathToRoot') . '/resources/users/' . $u->id
                                        . '.jpg" alt=""></div>' !!}
                    </div>
                    <span>{{ ($fromUser == $u->id) ? $userName = $u->name : $u->name }}</span>
                </div>
            </a>
@endif
@endforeach
@endforeach
        </div>
        <div class="rowRight">
            <div>
@foreach($lastMessages as $message)
                <div class="message{{ ($message->from_user == $user->id) ? ' myMessage clear' : '' }}">
                    <p><a href="{{ url('user/' . $message->from_user) }}">{{ ($message->from_user != $user->id)
                            ? $userName
                            : $user->name }} :: <span>{{ GeneralModel::getRussianDate($message->date) }}</span></a></p>
                    <p>{{ $message->text }}</p>
                </div>
@endforeach
            </div>
        </div>

        <div class="commentBody sendMessageField">
            <form action="{{ url('account/message-send') }}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="forUser" value="{{ $fromUser }}">
                <input type="hidden" name="fromUser" value="{{ $user->id }}">
                <textarea name="text" id="text" placeholder="Введите текст здесь"></textarea>
                <input type="submit" id="button" value="Отправить">
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var divRight = $('div.rowRight');
        // scroll to bottom messages
        divRight.scrollTop(divRight.prop('scrollHeight'));
    });
</script>
@endsection
