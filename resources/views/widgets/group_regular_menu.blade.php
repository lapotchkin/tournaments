<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.regular')
            <a class="nav-link active" href="#">Статистика</a>
        @else
            <a class="nav-link" href="{{ route('group.tournament.regular', ['groupTournament' => $tournament->id]) }}">
                Статистика
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (Route::currentRouteName() === 'group.tournament.regular.games')
            <a class="nav-link active" href="#">Расписание</a>
        @else
            <a class="nav-link"
               href="{{ route('group.tournament.regular.games', ['groupTournament' => $tournament->id]) }}">
                Расписание
            </a>
        @endif
    </li>
    @can('create', 'App\Models\GroupTournament')
        <li class="nav-item">
            @if (Route::currentRouteName() === 'group.tournament.regular.schedule')
                <a class="nav-link active" href="#">Расписание ВК</a>
            @else
                <a class="nav-link"
                   href="{{ route('group.tournament.regular.schedule', ['groupTournament' => $tournament->id]) }}">
                    Расписание ВК
                </a>
            @endif
        </li>
    @endcan
</ul>
