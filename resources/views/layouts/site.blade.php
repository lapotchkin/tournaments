<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('title')Киберспортивная лига</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('script')
        <script src="{!! mix('/js/bootstrap.js') !!}"></script>
        {{--        <script src="{!! mix('/js/common.js') !!}"></script>--}}
        {{--        <script src="{!! mix('/js/app.js') !!}"></script>--}}
    @show

    <link href="{{ url('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>

<div class="container">
    @section('header')
        <div class="row mt-3 mb-3">
            <h1 class="mt-1 mb-0">Киберспортивная лига</h1>

            {{--                <div class="row">--}}
            {{--                    <div class="col-4 text-muted">Верните поиск по алфавиту!</div>--}}
            {{--                    <div class="col-8 text-right">--}}
            {{--                        @auth--}}
            {{--                            <span class="fas fa-sign-out-alt"></span> <a href="{{ route('logout') }}">Выйти</a>--}}
            {{--                        @endauth--}}

            {{--                        @guest--}}
            {{--                            <span class="fa-stack fa-xs">--}}
            {{--                                <i class="fas fa-square fa-stack-2x text-primary"></i>--}}
            {{--                                <i class="fab fa-vk fa-stack-1x fa-inverse"></i>--}}
            {{--                            </span>--}}
            {{--                            <a href="{{ route('social.redirect', ['provider' => 'vkontakte']) }}">войти на сайт</a>--}}
            {{--                        @endguest--}}
            {{--                    </div>--}}
            {{--                </div>--}}
        </div>
    @show

    @section('menu')
    @show

    @section('submenu')
    @show

    @yield('content')

</div>
</body>
</html>