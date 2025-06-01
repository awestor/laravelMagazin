<header>
    <nav>
        <ul class="main-menu">
            <div class="left-group">
                <li class="left"><a href="{{ route('MainPage.index') }}">Web-market</a>

                <li class="left dropdown-toggle">
                    <span></span><span></span><span></span>
                </li>
            </div>
            <div class="right-group">
                <form action="{{ route('search') }}" method="GET" class="search">
                    <input type="text" name="query" placeholder="Введите название..." required>
                    <button type="submit">🔍</button>
                </form>

                <li class="right"><a href="{{ route('favorites.index') }}">❤️</a>
                <li class="right"><a href="{{ route('order.index') }}">Корзина</a>
                @if(Auth::check())
                    <li class="right"><a href="{{ route('account.show') }}"> {{ Auth::user()->name }} </a></li>
                @else
                    <li class="right"><a href="{{ route('login') }}">Аккаунт</a>
                @endif
                
            </div>    
        <ul class="main-menu">    
    </nav>
</header>
{{--<div class="empty"><br></div> --}}
