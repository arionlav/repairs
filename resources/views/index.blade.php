@extends('master')

@if (isset($categoryInfo))
    @section('title', $categoryInfo->meta_title)
    @section('keywords', $categoryInfo->meta_keyword)
    @section('description', $categoryInfo->meta_description)
@else
    @section('title', 'Сайт о ремонте, строительстве, дизайне: статьи, фото, правила, рекомендации, инструкции')
    @section('keywords', 'ремонт, строительство, статьи о ремонте, инструкции по ремонту, сайт строительной тематики')
    @section('description', 'Подробные статьи о ремонте, строительстве, дизайне, у нас Вы найдете множество инструкций и готовых решений касающихся ремонта и строительства')
@endif

@section('content')
@include('layouts.beauty')

@if (isset($showWelcome))
<div class="welcome">
    <p>Добро пожаловать</p>
    <p>На сайт о ремонте и строительстве <br> <span>o&ndash;remonte.org</span></p>
    <p>Вопрос ремонта и оформления жилья периодически тревожит каждого. С проблемой можно справиться самостоятельно или обратиться к специалистам. Для первого варианта понадобятся практические навыки и понимание строительных технологий.</p>
    <p>На нашем сайте Вы узнаете о традиционных способах ремонта, нестандартных решениях, инновационных материалах. Даже если судьбу функциональности и дизайна квартиры будет решать бригада строителей, знание, как это делается, позволит грамотно руководить работами и корректировать их, добиваясь желаемого результата.</p>
</div>
@endif
{!! (isset($categoryInfo)) ? '<div class="headerCategory"><h2>' . $categoryInfo->name . '</h2></div>' : '' !!}
<main>

@foreach ($posts as $post)
    @include('posts.post', ['post' => $post])
@endforeach

<div class="paginationContainer">
    {!! $posts->render() !!}
</div>
</main>
@endsection