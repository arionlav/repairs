@extends('admin.master', ['noIndex' => 1])

@section('title', 'Категории')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
<link rel="stylesheet"
      href="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/css/jquery.dataTables.css"
      type="text/css" />
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/leanModal.css" type="text/css" />
@endsection

@section('content')
<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/leanModal.js"></script>

<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        $('#tableOrders').DataTable({
            "columns": [
                {
                    "width": "20px"
                },
                {
                    "width": "20px"
                },
                null,
                null,
                null,
                null,
                null,
                null,
                {"orderable": false}
            ],

            "order"         : [[0,'asc']],
            "lengthMenu"    : [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Все"]],
            "pageLength"    : 25
        }).page({{ $dataTablesPage - 1 }}).draw(false);
    });

    var dataTablesPage = 1;
    var link = '{{ url('admin/category-modify') }}/' + dataTablesPage;
    $(function () {
        $('div.dataTables_paginate').on('click', function () {
            dataTablesPage = $('a.paginate_button.current').html();
            link = '{{ url('admin/category-modify') }}/' + dataTablesPage;
        });
    });
</script>

<div class="form clear">
    <h2>Категории</h2>
    <div class="lineDark"></div>

    <div id="createNewBeauty">
        <a href="{{ url('admin/category-create') }}">Создать Категорию</a>
    </div>
    <div class="lineDark"></div>
</div>
<div class="contentAdmin form">
    <form action="{{ url('admin/categories') }}" method="post">
        {!! csrf_field() !!}
        <div class="beautyTable">
            <table class="tableOrders hover order-column stripe hidden" id="tableOrders" style="width: 100%;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название категории</th>
                    <th>Раздел</th>
                    <th>Горизонтальное меню</th>
                    <th>Группа beauty</th>
                    <th>META Title</th>
                    <th>META Keywords</th>
                    <th>META Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>{{ $category->parentName }}
                    </td>
                    <td>{{ ($category->hide_horizontal) ? 'Показывать' : 'Не показывать' }}</td>
                    <td>{{ $category->beauty_id }}</td>
                    <td>{{ $category->meta_title }}</td>
                    <td>{{ $category->meta_keyword }}</td>
                    <td>{{ $category->meta_description }}</td>
                    <td class='showModify'><a rel='leanModal' id='{{ $category->id }}'></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
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

<div class="overlay" id="overlay">
    <div class="overlayContent categoryModify">
        <div id="orderModifyContent"></div>
        <div class="loading"></div>
    </div>
</div>

<script type="text/javascript"
        src="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/js/jquery.dataTables.js">
</script>
@endsection
