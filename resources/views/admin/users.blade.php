<?
$pathToDirAvatars = config('var.pathToRoot') . '/resources/users/';
?>
@extends('admin.master', ['noIndex' => 1])

@section('title', 'Пользователи')
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
                    "width": "20px"
                },
                null,
                null,
                {
                    "createdCell": function (td, cellData) {
                        if ( cellData > 1 ) {
                            $(td).css('color', 'crimson')
                        }
                    }
                },
                null,
                null,
                {
                    "createdCell": function (td, cellData) {
                        if ( cellData == "Нет" ) {
                            $(td).css('color', 'crimson')
                        }
                    }
                },
                null,
                null,
                {"orderable": false},
                {"orderable": false}
            ],

            "order"         : [[0,'desc']],
            "lengthMenu"    : [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Все"]],
            "pageLength"    : 25
        }).page({{ $dataTablesPage - 1 }}).draw(false);
    });

    var dataTablesPage = 1;
    var link = '{{ url('admin/user-modify') }}/' + dataTablesPage;
    $(function () {
        $('div.dataTables_paginate').on('click', function () {
            dataTablesPage = $('a.paginate_button.current').html();
            link = '{{ url('admin/user-modify') }}/' + dataTablesPage;
        });
    });
</script>

<div class="form clear">
    <h2>Пользователи</h2>
    <div class="lineDark"></div>
    <table class="tableOrders hover order-column stripe hidden" id="tableOrders" style="width: 100%;">
        <thead>
        <tr>
            <th>ID</th>
            <th>Ava</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Comm.</th>
            <th>Views</th>
            <th>Conf.</th>
            <th>Создан</th>
            <th>Обновлен</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>
                <div class="avatar">
                    {!! (!is_file(base_path() . '/resources/users/' . $user->id . '.jpg'))
                            ? "<div><img src='" . $pathToDirAvatars . "default.jpg' alt=''></div>"
                            : "<div><img src='" . $pathToDirAvatars . $user->id . ".jpg' alt=''></div>" !!}
                </div>
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ (isset($comments[$user->id])) ? $comments[$user->id] : 0 }}</td>
            <td>{{ $user->count_review }}</td>
            <td>{{ ($user->confirmed) ? 'Да' : 'Нет' }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
            <td class='showModify'><a rel='leanModal' id='{{ $user->id }}'></a></td>
            <td>
                <span class="deleteSpan" id="deleteUser"
                      href="{{ url('admin/user-delete/' . $user->id) }}"
                      data="{{ $user->id }}">DEL
                </span>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="overlay" id="overlay">
    <div class="overlayContent userModify">
        <div id="orderModifyContent"></div>
        <div class="loading"></div>
    </div>
</div>

<script type="text/javascript"
        src="{{ config('var.pathToRoot') }}/resources/js/data_tables/media/js/jquery.dataTables.js">
</script>

<script type="text/javascript">
    // Delete user
    $(document).ready(function () {
        $(document.body).on('click', 'span#deleteUser', function () {
            if (confirm("Вы подтверждаете удаление?")) {
                var id = $(this).attr('data');
                $.ajax({
                    url: "{{ url('admin/user-delete') }}/" + id,
                    type: 'post',
                    data: {
                        'id': id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        $('span#deleteUser[data="' + result + '"]').closest('tr').css({
                            color: '#bdc4d1'
                        });
                        alert('Удалили пользователя  ' + result);
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript" src="{{ config('var.pathToRoot') }}/resources/js/avatars.js"></script>
@endsection
