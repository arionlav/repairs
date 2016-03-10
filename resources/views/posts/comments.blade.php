<?
use App\Http\Models\GeneralModel;
use App\Http\Models\PostsModel;

$pathToDirAvatars = '/resources/users/';
?>
<section class="fullTextContainer" id="commentsDiv">
    <h2 class="commentsHeader">Комментарии <span>{{ count($comments) }}</span></h2>
    <!--noindex-->
@if (Auth::check())
    <div class="commentBox clear addCommentBox">
        <div class="avatar"><div><img src="{{
            (!is_file(base_path() . $pathToDirAvatars . ($userId = Auth::user()->id) . '.jpg'))
                ? $pathToAvatar = config('var.pathToRoot') . $pathToDirAvatars . 'default.jpg'
                : $pathToAvatar = config('var.pathToRoot') . $pathToDirAvatars . $userId . '.jpg'
            }}" alt=""></div></div>
        <div class="commentBody article">
            <form id="comment" action="{{ url('comment/send') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="idPost" value="{{ $postDb->id }}">
                <input type="hidden" name="idUser" value="{{ $userId }}">
                <input type="hidden" name="answerTo" value="0">
                <textarea name="text" id="text" placeholder="Ваш комментарий"></textarea>
                <input type="file" name="file" id="file" accept="image/jpeg,image/png,image/bmp">
                <input type="submit" id="button" value="Отправить">
            </form>
        </div>
        @if (count($errors) > 0)
            @include('auth.errorValidation', ['errors' => $errors, 'class' => 'errorComment'])
        @endif
    </div>
@else
    <div class="commentBox clear addCommentBox addCommentBoxNoUser">
        <p>Только <span>зарегистрированные</span> пользователи могут оставлять комментарии</p>
        <p>Чтобы оставить комментарий, пожалуйста,
            <a href="{{ url('auth/login') }}" rel="nofollow">войдите на сайт</a> или пройдите простой процесс
            <a href="{{ url('auth/register') }}" rel="nofollow">регистрации</a></p>
    </div>
@endif
    <!--/noindex-->

@if (! empty($comments))
@foreach($comments as $comment)
    <section class="commentBox clear" id="comment{{ $comment->id }}"
             style="margin-left: {{ $comment->gap * 40 }}px;">
        <div class="avatar">
            <a href="{{ url("user/" . $comment->id_user) }}">
            {!! (!is_file(base_path() . $pathToDirAvatars . $comment->id_user . '.jpg'))
                ? '<div><img src="' . config('var.pathToRoot') . $pathToDirAvatars . 'default.jpg" alt=""></div>'
                : '<div><img src="' . config('var.pathToRoot') . $pathToDirAvatars . $comment->id_user
                    . '.jpg" alt=""></div>' !!}
            </a>
        </div>
        <div class="commentBody">
            <header>
                <strong><a href="{{ url("user/" . $comment->id_user) }}">{{ $comment->name }}</a></strong>
                <span>{{ GeneralModel::getRussianDate($comment->date) }}</span>
            </header>
            <p>{{ htmlspecialchars_decode($comment->text) }}</p>
            {!! (! is_null($comment->img))
                ? '<img class="commentsImg" src="' . $comment->img . '" alt="">' : '' !!}
            <footer>
                <span id="commentLikes" class="commentLikes" data="{{ $comment->id }}">{{
                    ($comment->likes) ? $comment->likes : ''
                }}</span>
                <span id="answerToComment" data="{{ $comment->id }}" dataName="{{ $comment->name }}">ОТВЕТИТЬ</span>
            </footer>
        </div>
    </section>
@endforeach
@else
    <div class="commentBox clear addCommentBoxNoUser commentsEmpty">
        <p>Ваш комментарий может быть первым!</p>
    </div>
@endif
    <script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>
</section>
