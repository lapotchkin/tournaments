@extends('layouts.site')

@section('title', $team->name . ' — ')

@section('content')
    {{ Breadcrumbs::render('team', $team) }}
    <h2>
        <i class="fab fa-{{ $team->platform->icon }} {{ $team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $team->name }}
        @can('create', 'App\Models\Team')
            <a class="btn btn-primary" href="{{ route('team.edit', ['team' => $team->id]) }}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan
    </h2>

    <div class="row">
        <div class="col-12 col-lg">
            @if(count($team->players))
                <h3>Игроки команды</h3>
                <table id="team-players" class="table table-striped table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th style="width: 2rem;"></th>
                        <th>Игрок</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teamPlayers as $teamPlayer)
                        @include('partials.team_row')
                    @endforeach
                    </tbody>
                </table>
            @endif

            @can('update', $team)
                <form id="player-add">
                    <div class="form-inline">
                        <div class="input-group">
                            <label for="player_id" class="mr-2">Игрок</label>
                            <select id="player_id" class="form-control mr-3" name="player_id">
                                <option value="">--Не выбран--</option>
                                @foreach($nonTeamPlayers as $player)
                                    <option
                                            value="{{ $player->id }}">{{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="player-add-button">Добавить</button>
                    </div>
                </form>
            @endcan
        </div>

        @if($team->tournaments->count())
            <div class="col-12 col-lg-4 col-xl-5">
                <h3 class="mt-3">Командные турниры</h3>
                <ul class="fa-ul">
                    @foreach($team->tournaments as $tournament)
                        <li>
                            <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                            <a href="{{ route('group.tournament', ['groupTournament' => $tournament->id]) }}">{{ $tournament->title }}</a>
                            <span class="badge badge-secondary badge-pill">
                        {{ $tournament->min_players }} на {{ $tournament->min_players }}
                    </span>
                            @foreach($tournament->winners as $winner)
                                @if($winner->team_id === $team->id)
                                    <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                            </span>
                                @endif
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @if(count($players->items))
        <h3>Полевые игроки</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Игрок</th>
                <th class="text-right">ИГР</th>
                <th class="text-right">ОЧК</th>
                <th class="text-right">ГОЛ</th>
                <th class="text-right">ПЕР</th>
                <th class="text-right">+/-</th>
                <th class="text-right">БРОС/И</th>
                <th class="text-right">ОТБ/И</th>
                <th class="text-right">ПЕР/И</th>
                <th class="text-right">ПОТ/И</th>
                <th class="text-right">СИЛ/И</th>
                <th class="text-right">ВБР</th>
                <th class="text-right">ПАС</th>
                <th class="text-right">ШМИН/И</th>
                <th class="text-right">АТК</th>
                <th class="text-right">КОМ</th>
                <th class="text-right">ЗАЩ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($players->items as $player)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('player', ['player' => $player->id]) }}">{{ $player->tag }}</a>
                        <small>{{ $player->name }}</small>
                    </td>
                    <td class="text-right">{{ $player->games }}</td>
                    <td class="text-right">{{ $player->points }}</td>
                    <td class="text-right">{{ $player->goals }}</td>
                    <td class="text-right">{{ $player->assists }}</td>
                    <td class="text-right">
                        @if($player->rating_offense)
                            {{ $player->plus_minus > 0 ? '+' . $player->plus_minus : $player->plus_minus }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->shots_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->takeaways_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->interceptions_per_game ? $player->interceptions_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->giveaways_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->hits_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->faceoff_win_percent . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->interceptions_per_game ? $player->pass_percent . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->penalty_minutes_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_offense ? $player->rating_offense . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_teamplay ? $player->rating_teamplay . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $player->rating_defense ? $player->rating_defense . '%' : '—' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if(count($goalies->items))
        <h3>Вратари</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Игрок</th>
                <th class="text-right">ИГР</th>
                <th class="text-right">ПОБ</th>
                <th class="text-right">ПОР</th>
                <th class="text-right">БРОС</th>
                <th class="text-right">ОТБ</th>
                <th class="text-right">ГОЛ</th>
                <th class="text-right">%ОТБ</th>
                <th class="text-right">ГОЛ/И</th>
                <th class="text-right">СУХ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($goalies->items as $player)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('player', ['player' => $player->id]) }}">{{ $player->tag }}</a>
                        <small>{{ $player->name }}</small>
                    </td>
                    <td class="text-right">{{ $player->games }}</td>
                    <td class="text-right">{{ $player->wins }}</td>
                    <td class="text-right">{{ $player->lose }}</td>
                    <td class="text-right">{{ $player->shot_against }}</td>
                    <td class="text-right">{{ $player->shot_against - $player->goals_against }}</td>
                    <td class="text-right">{{ $player->goals_against }}</td>
                    <td class="text-right">{{ round(1 - $player->goals_against / $player->shot_against, 3) }}</td>
                    <td class="text-right">{{ round($player->goals_against / $player->games, 2) }}</td>
                    <td class="text-right">{{ $player->shotouts }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if($stats['games'])
        <h3 class="mt-3">Статистика команды</h3>
        <div class="row mt-3">
            <div id="gamesResults" class="col-6" style="height: 20rem;"></div>
            <div id="faceoff" class="col-6" style="height: 20rem;"></div>
        </div>
        <div class="row mt-3">
            <div id="penaltyFor" class="col-6" style="height: 20rem;"></div>
            <div id="penaltyAgainst" class="col-6" style="height: 20rem;"></div>
        </div>
        <div class="row">
            <div id="scoreDynamics" class="col" style="height: 20rem;"></div>
        </div>
    @endif
@endsection

@section('script')
    @parent
    <style>
        .fa-stack {
            font-size: 0.5rem;
        }

        i {
            vertical-align: middle;
        }
    </style>

    @can('update', $team)
        <script src="{!! mix('/js/teamManagerModule.js') !!}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                const url = {
                    addPlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                    addPlayerRedirect: '{{ route('team', ['team' => $team->id])}}',
                    updatePlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                    deletePlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                };
                const templates = {
                    row: `@include('partials.team_row', ['teamPlayer' => null])`
                };

                TRNMNT_playoffModule.init(url, templates);
            });
        </script>
    @endcan

    <script src="{!! mix('/js/amcharts.js') !!}"></script>
    <script type="text/javascript">
        am4core.ready(function () {
            makePie({
                element: "gamesResults",
                data: {!! json_encode($stats['gamesResults'], JSON_UNESCAPED_UNICODE) !!},
                title: "Результаты игр",
                showLabel: true,
                legendTextFormat: "{value.value}",
            });
            makePie({
                element: "faceoff",
                data: {!! json_encode($stats['faceoff'], JSON_UNESCAPED_UNICODE) !!},
                title: "Вбрасывания",
            });
            makePie({
                element: "penaltyFor",
                data: {!! json_encode($stats['penaltyFor'], JSON_UNESCAPED_UNICODE) !!},
                title: "Реализация большинства",
            });
            makePie({
                element: "penaltyAgainst",
                data: {!! json_encode($stats['penaltyAgainst'], JSON_UNESCAPED_UNICODE) !!},
                title: "Нейтрализация меньшинства",
            });

            //Score
            let scoreDynamicsChart = am4core.create("scoreDynamics", am4charts.XYChart);
            scoreDynamicsChart.data = {!! json_encode($scoreDynamics, JSON_UNESCAPED_UNICODE) !!};
            // Create axes
            let categoryAxis = scoreDynamicsChart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "index";
            categoryAxis.title.text = "Игры";
            categoryAxis.renderer.labels.template.disabled = true;
            let valueAxis = scoreDynamicsChart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Количество";
            // Add cursor
            scoreDynamicsChart.cursor = new am4charts.XYCursor();

            var series2 = scoreDynamicsChart.series.push(new am4charts.LineSeries());
            series2.name = "Броски по воротам";
            // series2.stroke = am4core.color("#CDA2AB");
            series2.strokeWidth = 3;
            series2.dataFields.valueY = "shots";
            series2.dataFields.categoryX = "index";
            series2.fillOpacity = 0.6;
            series2.strokeWidth = 2;
            // series2.stacked = true;
            series2.tooltipText = "Броски: [bold]{valueY}[/]";

            var series1 = scoreDynamicsChart.series.push(new am4charts.LineSeries());
            series1.name = "Забитые голы";
            // series1.stroke = am4core.color("#CDA2AB");
            series1.strokeWidth = 3;
            series1.dataFields.valueY = "goals_for";
            series1.dataFields.categoryX = "index";
            series1.fillOpacity = 0.6;
            series1.strokeWidth = 2;
            // series1.stacked = true;
            series1.tooltipText = "Голы: [bold]{valueY}[/]";

            let scoreDynamicsChartTitle = scoreDynamicsChart.titles.create();
            scoreDynamicsChartTitle.text = "Динамика бросков и забитых голов";
            scoreDynamicsChartTitle.fontSize = 19;
        });

        function makePie(params) {
            const chart = am4core.create(params.element, am4charts.PieChart);
            chart.data = params.data;
            const pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "value";
            pieSeries.dataFields.category = "category";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.labels.template.disabled = true;
            // pieSeries.ticks.template.disabled = true;
            pieSeries.slices.template.propertyFields.fill = "color";

            chart.legend = new am4charts.Legend();
            // gamesResultsChart.legend.fontSize = 10;
            if (params.legendTextFormat) {
                pieSeries.legendSettings.valueText = params.legendTextFormat;
            }
            chart.legend.valueLabels.template.align = "right";
            chart.legend.valueLabels.template.textAlign = "end";
            chart.legend.itemContainers.template.paddingTop = 0;
            chart.legend.itemContainers.template.paddingBottom = 4;

            if (params.showLabel) {
                chart.innerRadius = am4core.percent(35);
                let label = pieSeries.createChild(am4core.Label);
                label.horizontalCenter = "middle";
                label.verticalCenter = "middle";
                label.fontSize = 40;
                label.text = "{values.value.sum}";
            }

            let title = chart.titles.create();
            title.text = params.title;
            title.fontSize = 19;
        }
    </script>
@endsection
