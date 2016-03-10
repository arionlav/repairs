<h2>Редактируем Пользователя <span><span>#</span>{{ $user->id }}</span></h2>
<div class="lineDark"></div>
<form action="{{ url('admin/user-modify') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $user->id }}">
    <input type="hidden" name="dataTablesPage" value="{{ $dataTablesPage }}">

    <div id="textFieldAdmin">
        <label for="name" class="largeLabel">Имя</label>
        <input type="text" name="name" id="name" value="{{ $user->name }}">
    </div>

    <div id="textFieldAdmin">
        <label for="email" class="largeLabel">Email</label>
        <input type="text" name="email" id="email" value="{{ $user->email }}">
    </div>

    <div id="textFieldAdmin">
        <label for="role" class="largeLabel">Роль</label>
        <input type="text" name="role" id="role" value="{{ $user->role }}">
    </div>

    @if (! $user->confirmed)
        <div id="textFieldAdmin">
            <input type="checkbox" name="confirmed" id="confirmed">
            <label for="confirmed">Подтвердить пользователя</label>
        </div>
    @endif

    @if (is_file(base_path() . '/resources/users/' . $user->id . '.jpg'))
        <div id="textFieldAdmin" class="inputAvatar">
            <input type="checkbox" name="avatar" id="avatar">
            <label for="avatar">Удалить аватарку</label>
        </div>
    @endif

    <button type="submit" id="button" class="leanModalButton">
        <div class="real_button">
            <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
            <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
            <div class="overlayHoverButtonRed"></div>
        </div>
    </button>
</form>