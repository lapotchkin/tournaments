<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        @if (strstr(Route::getCurrentRoute()->getActionName(), '@teams'))
            <a class="nav-link active" href="#">Команды</a>
        @else
            <a class="nav-link" href="{{ action('Site\GroupController@teams', ['tournamentId' => $tournament->id]) }}">
                Команды
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::getCurrentRoute()->getActionName(), 'GroupRegularController@index'))
            <a class="nav-link active" href="#">Чемпионат</a>
        @else
            <a class="nav-link"
               href="{{ action('Site\GroupRegularController@index', ['tournamentId' => $tournament->id]) }}">
                Чемпионат
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::getCurrentRoute()->getActionName(), 'GroupPlayoffController@index'))
            <a class="nav-link active" href="#">Плэйофф</a>
        @else
            <a class="nav-link"
               href="{{ action('Site\GroupPlayoffController@index', ['tournamentId' => $tournament->id]) }}">
                Плэйофф
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if (strstr(Route::getCurrentRoute()->getActionName(), '@copypaste'))
            <a class="nav-link active" href="#">Данные для ВК</a>
        @else
            <a class="nav-link"
               href="{{ action('Site\GroupController@copypaste', ['tournamentId' => $tournament->id]) }}">
                Данные для ВК
            </a>
        @endif
    </li>
</ul>