<?
use App\Http\Models\GeneralModel;
?>
<div class="beauty clear">
@foreach (GeneralModel::getBeauty((! isset($idCategory)) ? $idCategory = 1 : $idCategory) as $beauty)
<? $post = GeneralModel::getLikes($beauty->id_post) ?>
    <a href="{{ GeneralModel::getPostUrl($beauty->id_post, $post->header) }}">
        <div class="imgContainer img{{ $beauty->number }} clear">
            <div class="hvr-rectangle-out hoverBeauty"></div>
            <div class="hoverBeautyImg"></div>
            <span id="beautyGradient"></span>
            <span id="beautyImg" style="background-image: url('{{ config('var.pathToRoot') }}/resources/img/beauty/{{
                    $beauty->id }}.jpg')"></span>
            <span id="beautyText">{{ $beauty->header }} {!! ($beauty->description) ? '<br /><em>'
                    . $beauty->description . '</em>' : '' !!}</span>
            <span id="beautyRating">&hearts; {{ $post->likes }}</span>
        </div>
    </a>
@endforeach
</div>