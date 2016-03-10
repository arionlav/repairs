@extends('master', ['noIndex' => 1])

@section('title', 'Панель администратора')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
@endsection

@section('content')
@include('layouts.beauty')
<div class="form clear">
    <h2>Добро пожаловать в админку!</h2>
    <div class="lineDark"></div>
    <div class="adminLists">
        <ul>
            <a href="{{ url('admin/create') }}"><li>Написать статью</li></a>
            <a href="{{ url('admin/modify') }}"><li>Редактировать статью</li></a>
            <a href="{{ url('admin/delete') }}"><li>Удалить статью</li></a>
            <a href="{{ url('admin/comments') }}"><li>Комментарии</li></a>
            <a href="{{ url('admin/users') }}"><li>Пользователи</li></a>
            <a href="{{ url('admin/categories') }}"><li>Разделы</li></a>
            <a href="{{ url('admin/beauty') }}"><li>Beauty</li></a>
        </ul>
    </div>

</div>
@endsection