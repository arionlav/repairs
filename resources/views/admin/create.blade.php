@extends('admin.master', ['noIndex' => 1])

@section('title', 'Создание / Редактирование статьи')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
@endsection

@section('content')
<div class="form clear">
    <h2>Создание / Редактирование статьи</h2>
    <div class="lineDark"></div>
    <form method="POST" id="formAdmin" action="{{ url('admin/insert') }}"
          enctype="multipart/form-data" target="_blank">
        {!! csrf_field() !!}
        @if (isset($id))
            <input type="hidden" name="postId" value="{{ $id }}">
        @endif

        <div id="textFieldAdmin">
            <label for="header" class="largeLabel">Заголовок</label>
            <input type="text" name="header" id="header" value="{{ (isset($post)) ? $post->header : '' }}">
        </div>

        <div class="categories">
            <label for="category">Категория</label>
            <select name="category" id="category">
            @foreach($allCategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
            </select>
        </div>

        <div id="textFieldAdmin">
            <label for="list" class="largeLabel">Содержание (через ;)</label>
            <textarea name="list" id="list" style="height: 100px;">{{(isset($post)) ? $post->list : '' }}</textarea>
        </div>

        <div id="textFieldAdmin">
            <label for="description" class="largeLabel">Краткое описание</label>
            @include('admin.bbCode', ['idTextArea' => 'description'])
            <textarea name="description" id="description" style="height: 100px;">{{
                (isset($post)) ? $post->description : ''
            }}</textarea>
        </div>

        <div id="textFieldAdmin">
            <label for="text" class="largeLabel">Текст статьи</label>
            @include('admin.bbCode', ['idTextArea' => 'text'])
            <textarea name="text" id="text" style="height: 850px;">{{
                (isset($post)) ? $post->text : ''
            }}</textarea>
        </div>

        <div id="textFieldAdmin">
            <label for="keywords" class="largeLabel">Теги</label>
            <input type="text" name="keywords" id="keywords" value="{{ (isset($post)) ? $post->keywords : '' }}">
        </div>

        <div class="lineDark"></div>

        <div id="textFieldAdmin">
            <label for="metaKeywords" class="largeLabel">МЕТА ключевые слова</label>
            <textarea name="metaKeywords" id="metaKeywords" style="height: 70px;">{{
                (isset($post)) ? $post->meta_keywords : ''
            }}</textarea>
        </div>

        <div id="textFieldAdmin">
            <label for="metaDescription" class="largeLabel">МЕТА description</label>
            <textarea name="metaDescription" id="metaDescription" style="height: 70px;">{{
                (isset($post)) ? $post->meta_description : ''
            }}</textarea>
        </div>

        <div class="lineDark"></div>

        <div class="imagesForPost">
            <label for="fileMain">ГЛАВНАЯ КАРТИНКА: </label>
            <input type="file" name="fileMain" id="fileMain" accept="image/jpeg,image/png,image/bmp">

            <div class="lineDark"></div>

            @for($i = 1; $i <= config('var.maxImagesOnPage'); $i++)
                <label for="file{{ $i }}">:::{{ $i }}</label>
                <input type="file" name="file{{ $i }}" id="file{{ $i }}" accept="image/jpeg,image/png,image/bmp">
                <br>
            @endfor
        </div>

        <div class="lineDark"></div>

        <div class="adminButton">
            <div>
                <input type="checkbox" name="preview" id="preview" checked>
                <label for="preview">Предварительный просмотр</label>
            </div>

            @if (isset($updateDate))
                <div>
                    <input type="checkbox" name="updateDate" id="updateDate">
                    <label for="updateDate">Обновить дату</label>
                </div>
            @endif

            <button type="submit" id="button" class="registrationButton">
                <div class="real_button">
                    <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
                    <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
                    <div class="overlayHoverButtonRed"></div>
                </div>
            </button>
        </div>
    </form>
</div>
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/bbCode.js"></script>
@endsection