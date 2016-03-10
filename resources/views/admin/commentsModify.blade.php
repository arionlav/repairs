<h2>Редактируем комментарий <span><span>#</span>{{ $comment->id }}</span></h2>
<div class="lineDark"></div>
<form action="{{ url('admin/comments-modify') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $comment->id }}">
    @if (! is_null($comment->img))
    <input type="hidden" name="img" value="{{ $comment->img }}">
    @endif
    <input type="hidden" name="dataTablesPage" value="{{ $dataTablesPage }}">
    
    <label for="text">Текст комментария:</label>
    <textarea name="text" id="text">{{ $comment->text }}</textarea>

    <div id="textFieldAdmin">
        <label for="likes" class="largeLabel">Лайков: </label>
        <input type="text" name="likes" id="likes" value="{{ $comment->likes }}">
    </div>

    <div id="textFieldAdmin">
        <input type="checkbox" name="imgDel" id="imgDel">
        <label for="imgDel">Удалить картинку</label>
    </div>

    <button type="submit" id="button" class="leanModalButton">
        <div class="real_button">
            <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
            <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
            <div class="overlayHoverButtonRed"></div>
        </div>
    </button>
</form>