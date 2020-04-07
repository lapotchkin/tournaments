<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.playoff')
            <a class="nav-link active" href="#">Сетка</a>
        @else
            <a class="nav-link" href="{{ route('group.tournament.playoff', ['groupTournament' => $tournament->id]) }}">
                Сетка
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.playoff.stats')
            <a class="nav-link active" href="#">Статистика</a>
        @else
            <a class="nav-link"
               href="{{ route('group.tournament.playoff.stats', ['groupTournament' => $tournament->id]) }}">
                Статистика
            </a>
        @endif
    </li>
    @can('create', 'App\Models\GroupTournament')
        <li class="nav-item">
            @if (Route::currentRouteName() === 'group.tournament.playoff.games')
                <a class="nav-link active" href="#">Расписание</a>
            @else
                <a class="nav-link"
                   href="{{ route('group.tournament.playoff.games', ['groupTournament' => $tournament->id]) }}">
                    Расписание
                </a>
            @endif
        </li>
    @endcan
</ul>
