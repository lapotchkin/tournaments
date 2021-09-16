@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.regular', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalRegularMenu', ['tournament' => $tournament])

    <form method="get" class="form-inline mb-2">
        <div class="form-group mr-2">
            <label class="control-label mr-2" for="toDate">Сравнить с датой</label>
            <input type="text" id="toDate" class="form-control" name="toDate"
                   value="{{ $dateToCompare ? str_replace(' 00:00:00', '', $dateToCompare) : '' }}"
                   readonly>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Применить</button>
        <a href="{{ route('personal.tournament.regular', ['personalTournament' => $tournament]) }}"
           class="btn btn-warning">Сбросить</a>
    </form>

    <h3>Турнирная таблица</h3>
    @foreach($divisions as $division => $position)
        @if(count($divisions) > 1)
            <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
        @endif
        <table id="players{{ $division }}" class="players table table-striped table-sm">
            <thead class="table-dark"></thead>
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
    @endforeach
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#toDate').datepicker(TRNMNT_helpers.getDatePickerSettings());

            @foreach($divisions as $division => $position)
            $('#players{{ $division }}').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.1/i18n/ru.json"
                },
                data: {!! json_encode($position) !!},
                columns: [
                    {data: 'place', title: ''},
                    {data: 'prevPlace', title: ''},
                    {data: 'player', title: 'Игрок'},
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
            @endforeach
        });
    </script>

    <style>
        .players.dataTable tbody tr:nth-child({{ $tournament->playoff_limit ? $tournament->playoff_limit : pow(2, $tournament->playoff_rounds) / count($divisions) }}) td {
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

        .players.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(2),
        .leaders.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(4) {
            text-align: left !important;
        }

        .players.dataTable tbody td:nth-child(3),
        .leaders.dataTable tbody td:nth-child(3) {
            font-weight: bold;
        }
    </style>
@endsection
