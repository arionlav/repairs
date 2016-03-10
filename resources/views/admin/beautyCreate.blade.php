@extends('admin.master', ['noIndex' => 1])

@section('title', 'Создаем группу')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
@endsection

@section('content')
<div class="form clear">
    <h2>Создаем группу</h2>
    <div class="lineDark"></div>
</div>
<div class="contentAdmin form">
    <form action="{{ url('admin/beauty-create') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="beautyTable">
            <table>
                <thead>
                <tr>
                    <th>Header</th>
                    <th>Description (only for num 1)</th>
                    <th>Num</th>
                    <th>Post</th>
                    <th>Image</th>
                </tr>
                </thead>
                <tbody>
                @for ($i = 1; $i <= 5; $i++)
                <tr>
                    <td id="first">
                        <div id="textField" class="inTable">
                            <input type="text" name="header{{ $i }}" id="header{{ $i }}">
                        </div>
                    </td>
                    <td id="second">
                        <div id="textField" class="inTable">
                            <input type="text" name="desc{{ $i }}" id="desc{{ $i }}">
                        </div>
                    </td>
                    <td id="third">
                        <select name="post{{ $i }}" id="post{{ $i }}" class="inTable">
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}">
                                    {{ $post->id }} - {{ $post->header }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td id="forth">
                        <div id="textField" class="inTable">
                            <input type="text" style="width: 50px;" name="num{{ $i }}" id="num{{ $i }}">
                        </div>
                    </td>
                    <td id="fifth">
                        <input style="width: 250px;" type="file" name="img{{ $i }}" id="img{{ $i }}">
                    </td>
                </tr>
                @endfor
                </tbody>
            </table>
        </div>

        <div id="textField" class="textField" style="margin-left: -30px;">
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
@endsection