@extends('layouts.site')

@section('title', $tournament->title . ': Статистика Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff.stats', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

    <form method="get" class="form-inline mb-2">
        <div class="form-group mr-2">
            <label class="control-label mr-2" for="toDate">Сравнить с датой</label>
            <input type="text" id="toDate" class="form-control" name="toDate"
                   value="{{ $dateToCompare ? str_replace(' 00:00:00', '', $dateToCompare) : '' }}"
                   readonly>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Применить</button>
        <a href="{{ route('group.tournament.playoff', ['groupTournament' => $tournament->id]) }}"
           class="btn btn-warning">Сбросить</a>
    </form>

    <h3 class="mt-3">Лучшие по очкам</h3>
    <table id="topPoints" class="leaders table table-striped table-sm">
        <thead class="thead-dark"></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">Лучшие по голам</h3>
    <table id="topGoals" class="leaders table table-striped table-sm">
        <thead class="thead-dark"></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">Лучшие по передачам</h3>
    <table id="topAssists" class="leaders table table-striped table-sm">
        <thead class="thead-dark"></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">Расширенные показатели по игрокам</h3>
    <table id="players" class="table table-striped table-sm">
        <thead class="thead-dark"></thead>
        <tbody></tbody>
    </table>
    <table class="table table-sm">
        <tbody>
        <tr>
            <td>И — сыграно матчей</td>
            <td>ГвБ — Голы в большинстве</td>
            <td>Бл — заблокировано бросков за игру</td>
            <td>СП — силовые приёмы</td>
        </tr>
        <tr>
            <td>О — очки</td>
            <td>ГвМ — голы в меньшинстве</td>
            <td>ОтбИ — отборов шайбы за игру</td>
            <td>СПИ — силовых приёмов за игру</td>
        </tr>
        <tr>
            <td>Г — голы</td>
            <td>ПГ — победные голы</td>
            <td>ПерИ — перехватов шайбы за игру</td>
            <td>ШМ — Штрафные минуты</td>
        </tr>
        <tr>
            <td>Б% — процент реализации бросков</td>
            <td>+/- — плюс/минус</td>
            <td>ПотИ — потерь шайбы за игру</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>П — голевые передачи</td>
            <td>Вб% — процент выигранных вбрасываний</td>
            <td>Пас% — процент успешных пасов</td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>

    <h3 class="mt-3">
        Вратари<br>
        <small class="text-muted">В таблице только вратари, сыгравшие не менее 25% от общего числа игр команды</small>
    </h3>
    <table id="goalies" class="leaders table table-striped table-sm">
        <thead class="thead-dark"></thead>
        <tbody></tbody>
    </table>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#toDate').datepicker(TRNMNT_helpers.getDatePickerSettings());

            $('#topPoints').DataTable({
                data: {!! json_encode($leaders->points) !!},
                columns: [
                    {data: 'place', 'title': ''},
                    {data: 'prevPlace', 'title': ''},
                    {data: 'player', title: 'Игрок'},
                    {data: 'position', title: ''},
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'Игры'},
                    {data: 'goals', title: 'Голы'},
                    {data: 'assists', title: 'Передачи'},
                    {data: 'points', title: 'Очки'}
                ],
                'ordering': false,
                'pageLength': 20,
            });

            $('#topGoals').DataTable({
                data: {!! json_encode($leaders->goals) !!},
                columns: [
                    {data: 'place', 'title': ''},
                    {data: 'prevPlace', 'title': ''},
                    {data: 'player', title: 'Игрок'},
                    {data: 'position', title: ''},
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'Игры'},
                    {data: 'goals', title: 'Голы'},
                    {data: 'assists', title: 'Передачи'},
                    {data: 'points', title: 'Очки'}
                ],
                'ordering': false,
                'pageLength': 20,
            });

            $('#topAssists').DataTable({
                data: {!! json_encode($leaders->assists) !!},
                columns: [
                    {data: 'place', 'title': ''},
                    {data: 'prevPlace', 'title': ''},
                    {data: 'player', title: 'Игрок'},
                    {data: 'position', title: ''},
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'Игры'},
                    {data: 'goals', title: 'Голы'},
                    {data: 'assists', title: 'Передачи'},
                    {data: 'points', title: 'Очки'}
                ],
                'ordering': false,
                'pageLength': 20,
            });

            $('#players').DataTable({
                data: {!! json_encode($leaders->assists) !!},
                columns: [
                    {data: 'player', title: 'Игрок'},
                    {data: 'position', title: ''},
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'И'},
                    {data: 'points', title: 'О'},
                    {data: 'goals', title: 'Г'},
                    // {data: 'shots', title: 'Б'},
                    {data: 'shots_percent', title: 'Б%'},
                    {data: 'assists', title: 'П'},
                    {data: 'power_play_goals', title: 'ГвБ'},
                    {data: 'shorthanded_goals', title: 'ГвМ'},
                    {data: 'game_winning_goals', title: 'ПГ'},
                    {data: 'plus_minus', title: '+/-'},
                    {data: 'faceoff_win_percent', title: 'Вб%'},
                    {data: 'blocks_per_game', title: 'БлИ'},
                    {data: 'takeaways_per_game', title: 'ОтбИ'},
                    {data: 'interceptions_per_game', title: 'ПерИ'},
                    {data: 'giveaways_per_game', title: 'ПотИ'},
                    {data: 'pass_percent', title: 'Пас%'},
                    {data: 'hits', title: 'СП'},
                    {data: 'hits_per_game', title: 'СПИ'},
                    {data: 'penalty_minutes', title: 'ШМ'},
                    {data: 'penalty_minutes_per_game', title: 'ШМИ'},
                    // {data: 'rating_offense', title: 'НАП'},
                    // {data: 'rating_defense', title: 'ЗАЩ'},
                    // {data: 'rating_teamplay', title: 'КОМ'},
                ],
                'ordering': true,
                'pageLength': 20,
            });

            $('#goalies').DataTable({
                data: {!! json_encode($goalies) !!},
                columns: [
                    {'data': 'place', 'title': ''},
                    {'data': 'prevPlace', 'title': ''},
                    {'data': 'goalie', 'title': 'Вратарь'},
                    {'data': 'team', 'title': 'Команда'},
                    {'data': 'games', 'title': 'И'},
                    {'data': 'wins', 'title': 'ПОБ'},
                    {'data': 'loses', 'title': 'ПОР'},
                    {'data': 'shot_against', 'title': 'БРОС'},
                    {'data': 'saves', 'title': 'ОТБ'},
                    {'data': 'goal_against', 'title': 'Голы'},
                    {'data': 'saves_percent', 'title': 'КН'},
                    {'data': 'goal_against_per_game', 'title': 'ГОЛ/И'},
                    {'data': 'shootouts', 'title': 'СУХ'},
                ],
                'ordering': false,
                'paging': false,
                'searching': false,
                'info': false,
                'pageLength': -1,
            });
        });
    </script>

    <style>
        .teams.dataTable tbody tr:nth-child({{ pow(2, $tournament->playoff_rounds) }}) td {
            border-bottom: 3px red solid !important;
        }

        .dataTable thead th {
            text-align: center;
        }

        .dataTable thead th:nth-child(1),
        .dataTable thead th:nth-child(2),
        .dataTable thead th:nth-child(3),
        .leaders.dataTable thead th:nth-child(4) {
            text-align: left;
        }

        .dataTable tbody td {
            text-align: center;
        }

        .teams.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(2),
        .leaders.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(4),
        .leaders.dataTable tbody td:nth-child(5) {
            text-align: left !important;
        }

        .teams.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(3) {
            font-weight: bold;
        }

        #players.dataTable tbody td:nth-child(1),
        #players.dataTable tbody td:nth-child(2),
        #players.dataTable tbody td:nth-child(3) {
            text-align: left !important;
        }
    </style>
@endsection
