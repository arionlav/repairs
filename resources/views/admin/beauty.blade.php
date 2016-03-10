<?
use App\Http\Models\GeneralModel;
?>
@extends('admin.master', ['noIndex' => 1])

@section('title', 'Beauty')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/leanModal.css" type="text/css" />
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/leanModal.js"></script>
@endsection

@section('content')
<div class="form clear">
    <h2>Beauty</h2>
    <div class="lineDark"></div>
</div>

<div id="createNewBeauty">
    <a href="{{ url('admin/beauty-create') }}">Создать новую группу</a>
</div>
<div class="lineDark"></div>

<script type="text/javascript" language="javascript" class="init">
    var link = '{{ url('admin/beauty-modify') }}';
</script>

<div class="contentAdmin">
    <form action="{{ url('admin/beauty-change-image') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
    @foreach($beautyPrettyArray as $key => $beautyArray)
        <h2>#{{ $key }} <span><a rel='leanModal' id='{{ $key }}'>Редактировать группу</a></span></h2>

        <div class="beautyTable">
            <table>
                <thead>
                <tr>
                    <th>Num</th>
                    <th>Image</th>
                    <th>Post</th>
                    <th>Description for Num 1</th>
                </tr>
                </thead>
                <tbody>
                @foreach($beautyArray as $b)
                    <tr>
                        <td>{{ $b->number }}</td>
                        <td class="beautyImageTd" id="tdId{{ $b->id }}" style="width: 250px; padding: 5px;">
                            <img src="{{ config('var.pathToRoot') }}/resources/img/beauty/{{ $b->id }}.jpg"
                                 alt="{{ $b->id }}" style="margin-left: 25px;"
                                 title="{{ $b->id }}" id="imgId{{ $b->id }}">
                            <div style="height:0; overflow:hidden">
                                <input type="file" id="fileInput{{ $b->id }}" name="fileInput{{ $b->id }}" />
                            </div>
                        </td>
                        <td>
                            <a href="{{ GeneralModel::getPostUrl($b->id_post, $b->header) }}" target="_blank">
                                {{ $b->id_post }} - {{ $b->header }}
                            </a>
                        </td>
                        <td>{{ $b->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
        <button type="submit" id="button" class="registrationButton">
            <div class="real_button">
                <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
                <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>

<div class="overlay" id="overlay">
    <div class="overlayContent beautyModify">
        <div id="orderModifyContent"></div>
        <div class="loading"></div>
    </div>
</div>

<script type="text/javascript" src="{{ config('var.pathToRoot')}}/resources/js/admin/beautyChangeImage.js"></script>
@endsection