<footer class="footerContainer">
    <div class="footerContent">
        <div class="footerLogo">
            <div class="footerCopyright">&copy; {{ date('Y') }} | Все о ремонте и строительстве</div>
        </div>
        <!--noindex-->
        <div class="footerRow">
            <ul>
                <li><a href="{{ url('/') }}" rel="nofollow">Главная</a></li>
                <li><a href="{{ url('info') }}" rel="nofollow">Реклама на портале</a></li>
                @if (Auth::check())
                <li><a href='{{ url('auth/logout') }}' rel='nofollow'>Выйти</a></li>
                <li><a href='{{ url('account') }}' rel='nofollow'>Личный кабинет</a></li>
                @else
                <li><a href='{{ url('auth/login') }}' id='login' rel='nofollow'>Войти</a></li>
                <li><a href='{{ url('auth/register') }}' id='registration' rel='nofollow'>Регистрация</a></li>
                @endif
            </ul>
        </div>
        <div class="footerRow">
            <ul>
                <li><a href="{{ url('category1/steny') }}" rel="nofollow">Стены</a></li>
                <li><a href="{{ url('category2/potolok') }}" rel="nofollow">Потолок</a></li>
                <li><a href="{{ url('category4/vybor-materiala') }}" rel="nofollow">Выбор материалов</a></li>
            </ul>
        </div>
        <div class="footerRow">
            <ul>
                <li><a href="{{ url('category3/interer') }}" rel="nofollow">Интерьер</a></li>
                <li><a href="{{ url('category5/eto-interesno') }}" rel="nofollow">Это интересно!</a></li>
            </ul>
        </div>
        <!--/noindex-->
    </div>
</footer>
