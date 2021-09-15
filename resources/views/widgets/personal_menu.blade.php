<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'personal.tournament')
            <a class="nav-link active" href="#">Игроки</a>
        @else
            <a class="nav-link" href="{{ route('personal.tournament', ['personalTournament' => $tournament]) }}">
                Игроки
            </a>
        @endif
    </li>
    @auth
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                @if (Route::currentRouteName() === 'personal.tournament.map')
                    <a class="nav-link active" href="#">Карта игроков</a>
                @else
                    <a class="nav-link"
                       href="{{ route('personal.tournament.map', ['personalTournament' => $tournament]) }}">
                        Карта игроков
                    </a>
                @endif
            </li>
        @endif
    @endauth
    <li class="nav-item">
        @if (strstr(Route::currentRouteName(), 'regular'))
            <a class="nav-link active" href="#">Чемпионат</a>
        @else
            <a class="nav-link"
               href="{{ route('personal.tournament.regular', ['personalTournament' => $tournament]) }}">
                Чемпионат
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::currentRouteName(), 'playoff'))
            <a class="nav-link active" href="#">Плэйофф</a>
        @else
            <a class="nav-link"
               href="{{ route('personal.tournament.playoff', ['personalTournament' => $tournament]) }}">
                Плэйофф
            </a>
        @endif
    </li>
    @auth
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                @if (Route::currentRouteName() === 'personal.tournament.copypaste')
                    <a class="nav-link active" href="#">Данные для ВК</a>
                @else
                    <a class="nav-link"
                       href="{{ route('personal.tournament.copypaste', ['personalTournament' => $tournament]) }}">
                        Данные для ВК
                    </a>
                @endif
            </li>
        @endif
    @endauth
</ul>
