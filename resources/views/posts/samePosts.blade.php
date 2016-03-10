<?
use App\Http\Models\GeneralModel;
?>
@foreach ($samePosts as $samePostsByKey)
@foreach ($samePostsByKey as $sp)
    <a href="{{ GeneralModel::getPostUrl($sp->id, $sp->header) }}">
        <li>{{ $sp->header }}
            <div class="postMoreInfo">{{ $ruDate }}<span class="countShowing">{{ $sp->review }}</span></div>
        </li>
    </a>
@endforeach
@endforeach