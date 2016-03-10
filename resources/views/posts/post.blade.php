<?
use App\Http\Models\GeneralModel;
use App\Http\Models\PostsModel;
?>
<article class="post clear">
    <header class="postTop">
        <div class="postTopLogo"></div>
        <div class="postTopInfo">
            <time datetime="{{ GeneralModel::getTimeDatetime($post->date) }}"
                  title="{{ $ruDate = GeneralModel::getRussianDate($post->date) }}">
                {{ $ruDate }}
            </time>
            <span class="countShowing">{{ $post->review }}</span>
        </div>
    </header>
    <div class="postPhoto">
        <a class="hvr-trim" href="{{ $url = GeneralModel::getPostUrl($post->id, $post->header) }}"
           title="{{ $post->header }}">
            <img src="{!! PostsModel::getMainImg($post->id) !!}" alt="{{ $post->header }}" title="{{ $post->header }}">
        </a>
    </div>
    <!--noindex-->
    <div class="tags">{!! PostsModel::getKeyWords($post->keywords) !!}</div>
    <!--/noindex-->
    <h1><a href="{{ $url }}">{{ $post->header }}</a></h1>

    <div class="shortDescContainer clear">
        <div class="line lineShortDescMenuTop" id="lineShortDescMenu"></div>
        <div class="shortDescMenu">
            <p>Содержание:</p>
            <ul>
                @include('posts.list', ['postList' => $post->list])
            </ul>
        </div>
        <div class="line lineShortDescMenuBottom" id="lineShortDescMenu"></div>
        <div class="shortDescText">
            <p>{!! htmlspecialchars_decode($post->description) !!}</p>
            <p class="readMore"><a href="{{ $url }}" title="Читать дальше статью">Читать дальше<span></span></a></p>
        </div>
    </div>
    <!--noindex-->
    <div class="line"></div>
    @include('posts.footerArticle', [
        'post'     => $post,
        'comments' => $post->comments,
        'url'      => $url,
        'header'   => $post->header
    ])
    <!--/noindex-->
</article>
