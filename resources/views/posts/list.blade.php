@foreach (explode(';', $postList) as $list)
    {!! ($list) ? '<a href="' . $url . '#sub' . ((isset($i) ? ++$i : $i = 1))
            . '"><li>' . trim($list) . '</li></a>' : '' !!}
@endforeach