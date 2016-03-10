<{{ (! isset($inFullText))
        ? $tag = 'footer'
        : $tag = 'div'
}} class="afterPost {{ isset($inFullText) ? 'afterPostBottom' : '' }}">
    <span class="afterPostLike" id="incrementLike" data="{{ $post->id }}">{{ $post->likes }}</span>
    <a href="{{ $url }}#commentsDiv"><span class="afterPostComments">{{ $comments }}</span></a>
    <span class="afterPostFeedback">Поделиться</span>

    <div class="ya-share2" async="async"
         data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,blogger,evernote,viber"
         data-title="{{ $header }}" data-url="{{ $url }}"></div>
</{{ $tag }}>
