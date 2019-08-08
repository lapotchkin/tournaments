<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament')
            <a class="nav-link active" href="#">Команды</a>
        @else
            <a class="nav-link" href="{{ route('group.tournament', ['tournamentId' => $tournament->id]) }}">
                Команды
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::currentRouteName(), 'regular'))
            <a class="nav-link active" href="#">Чемпионат</a>
        @else
            <a class="nav-link"
               href="{{ route('group.tournament.regular', ['tournamentId' => $tournament->id]) }}">
                Чемпионат
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::currentRouteName(), 'playoff'))
            <a class="nav-link active" href="#">Плэйофф</a>
        @else
            <a class="nav-link"
               href="{{ route('group.tournament.playoff', ['tournamentId' => $tournament->id]) }}">
                Плэйофф
            </a>
        @endif
    </li>
    @auth
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                @if (Route::currentRouteName() === 'group.tournament.copypaste')
                    <a class="nav-link active" href="#">Данные для ВК</a>
                @else
                    <a class="nav-link"
                       href="{{ route('group.tournament.copypaste', ['tournamentId' => $tournament->id]) }}">
                        Данные для ВК
                    </a>
                @endif
            </li>
        @endif
    @endauth
</ul>