<h2>Редактируем категорию <span><span>#</span>{{ $category->id }} {{ $category->name }}</span></h2>
<div class="lineDark"></div>
<form action="{{ url('admin/category-modify') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $category->id }}">
    <input type="hidden" name="dataTablesPage" value="{{ $dataTablesPage }}">

    <div id="textFieldAdminWide">
        <label for="name" class="largeLabel">Название категории</label>
        <input type="text" name="name" id="name" value="{{ $category->name }}">
    </div>

    <div id="textFieldAdminWide">
        <label for="beautyId">Группа beauty</label>
        <select name="beautyId" id="beautyId" class="inTable">
            @foreach($beauty as $b)
            <option value="{{ $b->group_beauty }}" {{
                    ($b->group_beauty == $category->beauty_id) ? 'selected' : '' }}>
                {{ $b->group_beauty }}
            </option>
            @endforeach
        </select>
    </div>

    <div id="textFieldAdminWide">
        <label for="categoryParent">Раздел</label>
        <select name="categoryParent" id="categoryParent" class="inTable">
            @foreach($categoriesParent as $с)
            <option value="{{ $с->id }}" {{
                    ($с->id == $category->main) ? 'selected' : '' }}>
                {{ $с->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div id="textFieldAdminWide">
        <label for="horizontalMenu">В горизонтальном меню</label>
        <select name="horizontalMenu" id="horizontalMenu" class="inTable">
            <option value="1" {{ ($category->hide_horizontal === 1) ? 'selected' : '' }}>Показывать</option>
            <option value="0" {{ ($category->hide_horizontal === 0) ? 'selected' : '' }}>Не показывать</option>
        </select>
    </div>

    <div id="textFieldAdminWide">
        <label for="metaTitle" class="largeLabel">META Title</label>
        <input type="text" name="metaTitle" id="metaTitle" value="{{ $category->meta_title }}">
    </div>

    <div id="textFieldAdminWide">
        <label for="metaKeywords" class="largeLabel">META Keywords</label>
        <input type="text" name="metaKeywords" id="metaKeywords" value="{{ $category->meta_keyword }}">
    </div>


    <div id="textFieldAdminWide" class="withTextarea">
        <label for="metaDescription">META Description</label>
        <textarea name="metaDescription" id="metaDescription">{{ $category->meta_description }}</textarea>
    </div>

    <button type="submit" id="button" class="leanModalButton">
        <div class="real_button">
            <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
            <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
            <div class="overlayHoverButtonRed"></div>
        </div>
    </button>
</form>