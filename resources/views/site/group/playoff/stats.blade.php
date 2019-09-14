@extends('layouts.site')

@section('title', $tournament->title . ': Статистика Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff.stats', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

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
        .leaders.dataTable tbody td:nth-child(4) {
            text-align: left !important;
        }

        .teams.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(3) {
            font-weight: bold;
        }
    </style>
@endsection