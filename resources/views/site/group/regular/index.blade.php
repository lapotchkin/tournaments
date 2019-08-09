@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

    <h3>Турнирная таблица</h3>
    <table id="teams" class="teams table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>
    <table class="table table-sm">
        <tbody>
        <tr>
            <td>И — сыграно матчей</td>
            <td>ВОТ — выигрыши в овертайме</td>
            <td>ПБ — проигрыши по буллитам</td>
            <td>ЗШ — заброшенные шайбы</td>
        </tr>
        <tr>
            <td>О — очки</td>
            <td>ВБ — выигрыши по буллитам</td>
            <td>П — проигрыши в основное время</td>
            <td></td>
        </tr>
        <tr>
            <td>В — выигрыши в основное время</td>
            <td>ПОТ — проигрыши в овертайме</td>
            <td>РШ — разница шайб</td>
            <td></td>
        </tr>
        </tbody>
    </table>

    <h3 class="mt-3">Статистика команд</h3>
    <table id="teamsExtended" class="teams table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>
    <table class="table table-sm">
        <tr>
            <td>ЗШ/И — Заброшено щайб за игру</td>
            <td>ПК — Нейтрализация меньшинства</td>
            <td>Вб — Выигранные вбрасывания</td>
            <td>ЗШМ — Заброшено шайб в меньшинстве</td>
        </tr>
        <tr>
            <td>ПШ/И — пропущено шайб за игру</td>
            <td>Бр/И — Броски по воротам соперника за игру</td>
            <td>Сил/И — Силовых приёмов за игру</td>
            <td>ВА/И — Время в атаке за игру</td>
        </tr>
        <tr>
            <td>ПП — Реализация большинства</td>
            <td>БрП/И — Броски по своим воротам за игру</td>
            <td>СилП/И — Пропущено силовых приёмов за игру</td>
            <td></td>
        </tr>
    </table>

    <h3 class="mt-3">Лучшие по очкам</h3>
    <table id="topPoints" class="leaders table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">Лучшие по голам</h3>
    <table id="topGoals" class="leaders table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">Лучшие по передачам</h3>
    <table id="topAssists" class="leaders table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>

    <h3 class="mt-3">
        Вратари<br>
        <small class="text-muted">В таблице только вратари, сыгравшие не менее 25% от общего числа игр команды</small>
    </h3>
    <table id="goalies" class="leaders table table-striped table-sm">
        <thead></thead>
        <tbody></tbody>
    </table>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#teams').DataTable({
                data: {!! json_encode($position) !!},
                columns: [
                    {data: 'place', title: ''},
                    {data: 'prevPlace', title: ''},
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'И'},
                    {data: 'points', title: 'О'},
                    {data: 'wins', title: 'В'},
                    {data: 'wins_ot', title: 'ВОТ'},
                    {data: 'wins_so', title: 'ВБ'},
                    {data: 'lose_ot', title: 'ПОТ'},
                    {data: 'lose_so', title: 'ПБ'},
                    {data: 'lose', title: 'П'},
                    {data: 'goals_diff', title: 'РШ'},
                    {data: 'goals', title: 'ЗШ'}
                ],
                'ordering': false,
                'paging': false,
                'searching': false,
                'info': false,
                'pageLength': -1
            });

            $('#teamsExtended').DataTable({
                data: {!! json_encode($position) !!},
                columns: [
                    {'data': 'place', 'title': ''},
                    {'data': 'prevPlace', 'title': ''},
                    {'data': 'team', 'title': 'Команда'},
                    {'data': 'games', 'title': 'И'},
                    {'data': 'goals_per_game', 'title': 'ЗШ/И'},
                    {'data': 'goals_against_per_game', 'title': 'ПШ/И'},
                    {'data': 'powerplay', 'title': 'ПП'},
                    {'data': 'penalty_kill', 'title': 'ПК'},
                    // {'data': 'shots_for', 'title': 'Бр'},
                    // {'data': 'shots_against', 'title': 'БрП'},
                    {'data': 'shots_for_per_game', 'title': 'Бр/И'},
                    {'data': 'shots_against_per_game', 'title': 'БрП/И'},
                    {'data': 'faceoff', 'title': 'Вб'},
                    {'data': 'hit_for_per_game', 'title': 'Сил/И'},
                    {'data': 'hit_against_per_game', 'title': 'СилП/И'},
                    {'data': 'shorthanded_goal', 'title': 'ЗШМ'},
                    {'data': 'attack_time', 'title': 'ВА/И'},
                    {'data': 'pass_percent', 'title': 'Пас'}
                ],
                'ordering': false,
                'paging': false,
                'searching': false,
                'info': false,
                'pageLength': -1
            });

            $('#topPoints').DataTable({
                data: {!! json_encode($leaders->points) !!},
                columns: [
                    {data: 'place', 'title': ''},
                    {data: 'prevPlace', 'title': ''},
                    {data: 'player', title: 'Игрок'},
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
                    {data: 'team', title: 'Команда'},
                    {data: 'games', title: 'Игры'},
                    {data: 'goals', title: 'Голы'},
                    {data: 'assists', title: 'Передачи'},
                    {data: 'points', title: 'Очки'}
                ],
                'ordering': false,
                'pageLength': 20,
            });

           $('#goalies').DataTable({
                data: {!! json_encode($goalies) !!},
                columns: [
                    {'data': 'place', 'title': ''},
                    {'data': 'prevPlace', 'title': ''},
                    {'data': 'goalie', 'title': 'Вратарь'},
                    {'data': 'team', 'title': 'Команда'},
                    {'data': 'games', 'title': 'Игры'},
                    {'data': 'wins', 'title': 'Победы'},
                    {'data': 'loses', 'title': 'Поражения'},
                    {'data': 'shot_against', 'title': 'Броски'},
                    {'data': 'saves', 'title': 'Сэйвы'},
                    {'data': 'goal_against', 'title': 'Голы'},
                    {'data': 'saves_percent', 'title': 'Сэйвы %'},
                    {'data': 'goal_against_per_game', 'title': 'Гол/Игра'},
                    {'data': 'shootouts', 'title': 'Сухие'},
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
        .teams.dataTable tbody tr:nth-child(4) td {
            border-bottom: 3px red solid !important;
        }
        .dataTable thead th{
            text-align: center;
        }
        .dataTable thead th:nth-child(1),
        .dataTable thead th:nth-child(2),
        .dataTable thead th:nth-child(3),
        .leaders.dataTable thead th:nth-child(4){
            text-align: left;
        }

        .dataTable tbody td {
            text-align: center;
        }

        .teams.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(2),
        .leaders.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(4) {
            text-align: left !important;
        }
        .teams.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(3) {
            font-weight: bold;
        }

        #topPoints.dataTable tbody td:nth-child(8),
        #topGoals.dataTable tbody td:nth-child(6),
        #topAssists.dataTable tbody td:nth-child(7),
        #goalies.dataTable tbody td:nth-child(11) {
            background-color: #cce5ff !important;
            /*color: white !important;*/
        }
    </style>
@endsection