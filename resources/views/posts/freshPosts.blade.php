<?
use App\Http\Models\GeneralModel;
?>
<p class="rightHeader rightHeaderFresh">Свежие статьи</p>
<ul>
@foreach (GeneralModel::getFreshPosts() as $freshPost)
    <li>
        <a href="{{ GeneralModel::getPostUrl($freshPost->id, $freshPost->header) }}">{{ $freshPost->header }}</a>
        <span class="rightFreshSpan">{{ GeneralModel::getRussianDate($freshPost->date) }}</span>
    </li>
@endforeach
</ul>
