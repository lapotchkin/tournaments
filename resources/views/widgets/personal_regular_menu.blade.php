<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'personal.tournament.regular')
            <a class="nav-link active" href="#">Статистика</a>
        @else
            <a class="nav-link" href="{{ route('personal.tournament.regular', ['tournamentId' => $tournament->id]) }}">
                Статистика
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (Route::currentRouteName() === 'personal.tournament.regular.games')
            <a class="nav-link active" href="#">Расписание</a>
        @else
            <a class="nav-link"
               href="{{ route('personal.tournament.regular.games', ['tournamentId' => $tournament->id]) }}">
                Расписание
            </a>
        @endif
    </li>
    @auth
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                @if (Route::currentRouteName() === 'personal.tournament.regular.schedule')
                    <a class="nav-link active" href="#">Расписание ВК</a>
                @else
                    <a class="nav-link"
                       href="{{ route('personal.tournament.regular.schedule', ['tournamentId' => $tournament->id]) }}">
                        Расписание ВК
                    </a>
                @endif
            </li>
        @endif
    @endauth
</ul>
