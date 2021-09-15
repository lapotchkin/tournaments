<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'personal.tournament.regular')
            <a class="nav-link active" href="#">Статистика</a>
        @else
            <a class="nav-link" href="{{ route('personal.tournament.regular', ['personalTournament' => $tournament]) }}">
                Статистика
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (Route::currentRouteName() === 'personal.tournament.regular.games')
            <a class="nav-link active" href="#">Расписание</a>
        @else
            <a class="nav-link"
               href="{{ route('personal.tournament.regular.games', ['personalTournament' => $tournament]) }}">
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
                       href="{{ route('personal.tournament.regular.schedule', ['personalTournament' => $tournament]) }}">
                        Расписание ВК
                    </a>
                @endif
            </li>
        @endif
    @endauth
</ul>
