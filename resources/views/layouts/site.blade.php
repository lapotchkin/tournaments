<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('title')Киберспортивная лига</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @section('script')
        <script src="{!! mix('/js/bootstrap.js') !!}"></script>
        <script src="{!! mix('/js/app.js') !!}"></script>
        <link href="{!! mix('/css/app.css') !!}" rel="stylesheet" type="text/css">
    @show
</head>
{{--<body style="background-image: url({{ asset('images/pic/thumb10.jpg') }})">--}}
<body class="d-flex flex-column h-100">
<header>
    @section('header')
    @show

    @section('menu')
        <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark mb-3">
            <div class="container">
                <span class="navbar-brand mb-0 h1">Киберспортивная лига</span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Турниры</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('Site\TeamController@index') }}">Команды</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ action('Site\PlayerController@index') }}">Игроки</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Выйти
                                </a>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('social.redirect', ['provider' => 'vkontakte']) }}">
                                    <i class="fab fa-vk fa-inverse"></i> Войти
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    @show

    @section('submenu')
        @if(strstr(url()->current(), 'group'))
            <div class="container">
                @widget('groupGamesCarousel')
            </div>
        @endif
    @show
</header>

<main role="main" class="flex-shrink-0 mb-5">
    <div class="container">
        @yield('content')
    </div>
</main>

{{--<footer class="footer mt-auto py-3">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm">&copy; Киберспортивная лига {{ date('Y') }}</div>--}}
{{--            <div class="col-sm text-right">Работает на <a href="https://laravel.com">Laravel</a></div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</footer>--}}
</body>
</html>
