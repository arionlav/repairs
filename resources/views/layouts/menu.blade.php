<ul>
    <li>
        <a href="{{ url('/') }}"{!! (isset($noFollow)) ? $noFollow = ' rel="nofollow"' : $noFollow = '' !!}>Главная</a>
    </li>
    <li>
        <span class="dropDownSpan horizontalHide">Ремонт помещений<span style="display: none;"></span></span>
        <ul class="showDropDown">
            <li><a href="{{ url('category1/steny') }}"{!! $noFollow !!}>Стены</a></li>
            <li><a href="{{ url('category2/potolok') }}"{!! $noFollow !!}>Потолок</a></li>
        </ul>
    </li>
    <li>
        <span class="dropDownSpan horizontalHide">Дизайн и стиль<span style="display: none;"></span></span>
        <ul class="showDropDown">
            <li><a href="{{ url('category3/interer') }}"{!! $noFollow !!}>Интерьер</a></li>
        </ul>
    </li>
    <li><a href="{{ url('category4/vybor-materiala') }}"{!! $noFollow !!}>Выбор материалов</a></li>
    <li><a href="{{ url('category5/eto-interesno') }}"{!! $noFollow !!}>Это интересно!</a></li>
</ul>
