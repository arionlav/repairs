<?
use App\Http\Models\GeneralModel;
?>
@extends('admin.master', ['noIndex' => 1])

@section('title', 'Комментарии')
@section('keywords', '')
@section('description', '')
@section('header')
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/admin.css" type="text/css" />
@endsection

@section('content')
<link rel="stylesheet"
      href="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/css/jquery.dataTables.css"
      type="text/css" />
<link rel="stylesheet" href="{{ config('var.pathToRoot') }}/resources/css/leanModal.css" type="text/css" />

<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/leanModal.js"></script>

<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        $('#tableOrders').DataTable({
            "columns": [
                {
                    "width": "20px"
                },
                {
                    "width": "600px"
                },
                {
                    "width": "250px"
                },
                null,
                null,
                null,
                {
                    "width": "30px"
                },
                {
                    "width": "20px", "targets": [ 0 ]
                },
                {"orderable": false},
                {"orderable": false}
            ],

            "order"         : [[0,'desc']],
            "lengthMenu"    : [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Все"]],
            "pageLength"    : 10
        }).page({{ $dataTablesPage - 1 }}).draw(false);
    });

    var dataTablesPage = 1;
    var link = '{{ url('admin/comments-modify') }}/' + dataTablesPage;
    $(function () {
        $('div.dataTables_paginate').on('click', function () {
            dataTablesPage = $('a.paginate_button.current').html();
            link = '{{ url('admin/comments-modify') }}/' + dataTablesPage;
        });
    });
</script>

<div class="form clear">
    <h2>Комментарии</h2>
    <div class="lineDark"></div>
    <table class="tableOrders hover order-column stripe hidden" id="tableOrders" style="width: 100%;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Текст комментария</th>
            <th>For post</th>
            <th>User</th>
            <th>To</th>
            <th>Likes</th>
            <th>img</th>
            <th>Дата</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
        <tr>
            <td>{{ $comment->id }}</td>
            <td>{{ $comment->text }}</td>
            <td>
                <a href="{{ GeneralModel::getPostUrl($comment->id_post,
                        $comment->postHeader) }}#comment{{ $comment->id }}" target="_blank">
                    {{ $comment->id_post }} - {{ $comment->postHeader }}
                </a>
            </td>
            <td>{{ $comment->id_user }} - {{ $comment->userName }}</td>
            <td>{{ ($comment->answer_to != 0) ? $comment->answer_to : '' }}</td>
            <td>{{ ($comment->likes != 0) ? $comment->likes : '' }}</td>
            <td>{{ ($comment->img) ? '<a href="' . $comment->img . '">img</a>' : '' }}</td>
            <td>{{ GeneralModel::getRussianDate($comment->date, true) }}</td>
            <td class='showModify'><a rel='leanModal' id='{{ $comment->id }}'></a></td>
            <td>
                <span class="deleteSpan" id="deleteComment"
                      href="{{ url('admin/comment-delete/' . $comment->id) }}"
                      data="{{ $comment->id }}">DEL</span>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="overlay" id="overlay">
    <div class="overlayContent commentsModify">
        <div id="orderModifyContent"></div>
        <div class="loading"></div>
    </div>
</div>

<script type="text/javascript"
        src="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/js/jquery.dataTables.js">
</script>

<script type="text/javascript">
    // Delete comment
    $(document).ready(function () {
        $(document.body).on('click', 'span#deleteComment', function () {
            if (confirm("Вы подтверждаете удаление?")) {
                var id = $(this).attr('data');
                $.ajax({
                    url: "{{ url('admin/comment-delete') }}/" + id,
                    type: 'post',
                    data: {
                        'id': id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        for (var i in result) {
                            var selector = $('span#deleteComment[data="' + result[i] + '"]').closest('tr');
                            changeColor(selector);
                        }
                        alert(result);
                    }
                });
            }
        });

        function changeColor(selector)
        {
            var newColor = '#bdc4d1';
            selector.css({
                color: newColor
            });
            selector.find('a').css({
                color: newColor
            });
        }
    });
</script>
@endsection