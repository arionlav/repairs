@extends('admin.master', ['noIndex' => 1])

@section('title', 'Создаем категорию')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
@endsection

@section('content')
<div class="form clear">
    <h2>Создаем категорию</h2>
    <div class="lineDark"></div>
</div>
<div class="contentAdmin form">
    <form action="{{ url('admin/category-create') }}" method="post">
        {!! csrf_field() !!}

        <div id="textFieldAdminWide">
            <label for="name" class="largeLabel">Название категории</label>
            <input type="text" name="name" id="name" value="">
        </div>

        <div id="textFieldAdminWide">
            <label for="categoryParent">Раздел</label>
            <select name="categoryParent" id="categoryParent" class="inTable">
                @foreach($categoriesParent as $с)
                <option value="{{ $с->id }}">
                    {{ $с->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div id="textFieldAdminWide">
            <label for="horizontalMenu">В горизонтальном меню</label>
            <select name="horizontalMenu" id="horizontalMenu" class="inTable">
                <option value="1">Показывать</option>
                <option value="0">Не показывать</option>
            </select>
        </div>

        <div id="textFieldAdminWide">
            <label for="beautyId">Группа beauty</label>
            <select name="beautyId" id="beautyId" class="inTable">
                @foreach($beauty as $b)
                <option value="{{ $b->group_beauty }}">
                    {{ $b->group_beauty }}
                </option>
                @endforeach
            </select>
        </div>

        <div id="textFieldAdminWide">
            <label for="metaTitle" class="largeLabel">META Title</label>
            <input type="text" name="metaTitle" id="metaTitle" value="">
        </div>

        <div id="textFieldAdminWide">
            <label for="metaKeywords" class="largeLabel">META Keywords</label>
            <input type="text" name="metaKeywords" id="metaKeywords" value="">
        </div>


        <div id="textFieldAdminWide" class="withTextarea">
            <label for="metaDescription">META Description</label>
            <textarea name="metaDescription" id="metaDescription"></textarea>
        </div>

        <div id="textField" class="textField" style="margin-left: -30px;">
            <button type="submit" id="button" class="leanModalButton">
                <div class="real_button">
                    <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
                    <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
                    <div class="overlayHoverButtonRed"></div>
                </div>
            </button>
        </div>
    </form>
</div>
@endsection