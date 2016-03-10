<?
use App\Http\Models\GeneralModel;
?>
<p class="rightHeader rightHeaderPopular">Популярные статьи</p>
<ul>
@foreach (GeneralModel::getPopularPosts() as $popularPost)
    <li>
        <a href="{{ GeneralModel::getPostUrl($popularPost->id, $popularPost->header) }}"{!! (isset($noFollow))
                ? ' rel="nofollow"' : '' !!}>{{ $popularPost->header }}</a>
        <span>{{ $popularPost->likes }}</span>
    </li>
@endforeach
</ul>