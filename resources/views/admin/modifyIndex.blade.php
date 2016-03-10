@extends('admin.master', ['noIndex' => 1])

@section('title', 'Редактирование статьи')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
<link rel="stylesheet"
      href="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/css/jquery.dataTables.css"
      type="text/css" />
@endsection

@section('content')
<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        $('#tableOrders').DataTable({
            "columns": [
                null,
                null,
                {"orderable": false}
            ],
            "order"      : [[0,'desc']],
            "lengthMenu" : [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Все"]],
            "pageLength" : 25
        });
    });
</script>

<div class="form clear">
    <h2>Редактирование статьи</h2>
    <div class="lineDark"></div>
    <table class="tableOrders hover order-column stripe hidden" id="tableOrders" style="width: 100%;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Заголовок</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->header }}</td>
            <td class='showModify'><a href="{{ url('admin/modify-article/' . $post->id) }}" target="_blank"></a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript"
        src="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/js/jquery.dataTables.js">
</script>
@endsection
