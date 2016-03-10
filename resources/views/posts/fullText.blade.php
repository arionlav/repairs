<?
use App\Http\Models\GeneralModel;
use App\Http\Models\PostsModel;
?>
@extends('master')

@section('title', $postDb->header)
@section('keywords', $postDb->meta_keywords)
@section('description', $postDb->meta_description)
@section('header', '<link rel="stylesheet" href="../resources/css/core.css">')

@section('content')
<script type="text/javascript" src="../resources/js/core_boxgallery.js"></script>
<article class="fullTextContainer">
    <header class="postTop">
        <div class="postTopLogo"></div>
        <div class="postTopInfo">
            <time datetime="{{ GeneralModel::getTimeDatetime($postDb->date) }}"
                  title="{{ $ruDate = GeneralModel::getRussianDate($postDb->date) }}">
                {{ $ruDate }}
            </time>
            <span class="countShowing">{{ $postDb->review }}</span>
        </div>
    </header>
    <h1>{{ $postDb->header }}</h1>
    <div class="fullText">
        {!! htmlspecialchars_decode($post['text']) !!}
    </div>
    <div class="smallLine"></div>
    <div class="moreAbout">
        <p>Еще на эту тему:</p>
        <ul>
            @include('posts.samePosts', ['samePosts' => $samePosts, 'ruDate' => $ruDate])
        </ul>
    </div>
    <!--noindex-->
    <div class="smallLine"></div>
    <div class="textKeyWords">{!! PostsModel::getKeyWords($postDb->keywords) !!}</div>
    <div class="smallLine"></div>
    @include('posts.footerArticle', [
        'post'       => $postDb,
        'comments'   => count($post['comments']),
        'url'        => GeneralModel::getPostUrl($postDb->id, $postDb->header),
        'inFullText' => 1,
        'header'     => $postDb->header,
    ])
    <!--/noindex-->
</article>
@include('posts.comments', ['comments' => $post['comments']])
@endsection