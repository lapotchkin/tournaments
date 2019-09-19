<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.playoff')
            <a class="nav-link active" href="#">Сетка</a>
        @else
            <a class="nav-link" href="{{ route('group.tournament.playoff', ['tournamentId' => $tournament->id]) }}">
                Сетка
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.playoff.stats')
            <a class="nav-link active" href="#">Статистика</a>
        @else
            <a class="nav-link"
               href="{{ route('group.tournament.playoff.stats', ['tournamentId' => $tournament->id]) }}">
                Статистика
            </a>
        @endif
    </li>
    @auth
        @if(Auth::user()->isAdmin())
            <li class="nav-item">
                @if (Route::currentRouteName() === 'group.tournament.playoff.games')
                    <a class="nav-link active" href="#">Расписание</a>
                @else
                    <a class="nav-link"
                       href="{{ route('group.tournament.playoff.games', ['tournamentId' => $tournament->id]) }}">
                        Расписание
                    </a>
                @endif
            </li>
        @endif
    @endauth
</ul>
