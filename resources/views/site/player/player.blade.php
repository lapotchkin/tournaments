@extends('layouts.site')

@section('title', $player->tag . ($player->name ? ' (' . $player->name . ')' : '') . ' — ')

@section('content')
    {{ Breadcrumbs::render('player', $player) }}
    <h2>
        <i class="fab fa-{{ $player->platform->icon }} {{ $player->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $player->tag }} <small class="text-muted">{{ $player->name }}</small>
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('player.edit', ['player' => $player->id]) }}">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
        @endauth
    </h2>

    @if(count($personalStats->items))
        <h3 class="mt-3">Турниры 1 на 1</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Турнир</th>
                <th class="text-right">Победы</th>
                <th class="text-right">Проигрыши</th>
                <th class="text-right">Забито шайб</th>
                <th class="text-right">Пропущено шайб</th>
                <th class="text-right">Разница шайб</th>
            </tr>
            </thead>
            <tbody>
            @foreach($personalStats->items as $stats)
                <tr>
                    <td>
                        @if($stats->place)
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($stats->place) }}"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('personal.tournament', ['personalTournament' => $stats->id]) }}">{{ $stats->name }}</a>
                    </td>
                    <td class="text-right">{{ $stats->wins }}</td>
                    <td class="text-right">{{ $stats->lose }}</td>
                    <td class="text-right">{{ $stats->goals_for }}</td>
                    <td class="text-right">{{ $stats->goals_against }}</td>
                    <td class="text-right">
                        {{ ($stats->goals_for - $stats->goals_against) > 0 ? '+' : '' }}{{ $stats->goals_for - $stats->goals_against }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-right">{{ $personalStats->result->wins }}</th>
                <th class="text-right">{{ $personalStats->result->lose }}</th>
                <th class="text-right">{{ $personalStats->result->goals_for }}</th>
                <th class="text-right">{{ $personalStats->result->goals_against }}</th>
                <th class="text-right">{{ ($personalStats->result->goals_for - $personalStats->result->goals_against) > 0 ? '+' : '' }}{{ $personalStats->result->goals_for - $personalStats->result->goals_against }}</th>
            </tr>
            </tfoot>
        </table>
    @endif

    @if(count($teamStats->items))
        <h3>Полевой игрок EASHL</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Команда</th>
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
            @foreach($teamStats->items as $team)
                <tr>
                    <td>
                        @if($team->isActive)<i class="fas fa-users"></i>@endif
                    </td>
                    <td>
                        @if($team->id)
                            <a href="{{ route('team', ['team' => $team->id]) }}">{{ $team->name }}</a>
                        @else
                            {{ $team->name }}
                        @endif
                    </td>
                    <td class="text-right">{{ $team->games }}</td>
                    <td class="text-right">{{ $team->points }}</td>
                    <td class="text-right">{{ $team->goals }}</td>
                    <td class="text-right">{{ $team->assists }}</td>
                    <td class="text-right">
                        @if($team->rating_offense)
                            {{ $team->plus_minus > 0 ? '+' . $team->plus_minus : $team->plus_minus }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->shots_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->takeaways_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->interceptions_per_game ? $team->interceptions_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->giveaways_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->hits_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->faceoff_win_percent . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->interceptions_per_game ? $team->pass_percent . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->penalty_minutes_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->rating_offense . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_teamplay ? $team->rating_teamplay . '%' : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_defense ? $team->rating_defense . '%' : '—' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-right">{{ $teamStats->result->games }}</th>
                <th class="text-right">{{ $teamStats->result->points }}</th>
                <th class="text-right">{{ $teamStats->result->goals }}</th>
                <th class="text-right">{{ $teamStats->result->assists }}</th>
                <th class="text-right">
                    @if($teamStats->result->rating_offense)
                        {{ $teamStats->result->plus_minus > 0 ? '+' . $teamStats->result->plus_minus : $teamStats->result->plus_minus }}
                    @else
                        —
                    @endif
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->shots_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->takeaways_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->interceptions_per_game ? $teamStats->result->interceptions_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->giveaways_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->hits_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->faceoff_win_percent . '%' : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->interceptions_per_game ? $teamStats->result->pass_percent . '%' : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->penalty_minutes_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->rating_offense . '%' : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_teamplay ? $teamStats->result->rating_teamplay . '%' : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_defense ? $teamStats->result->rating_defense . '%' : '—' }}
                </th>
            </tr>
            </tfoot>
        </table>
    @endif

    @if(count($goalieStats->items))
        <h3>Вратарь EASHL</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th></th>
                <th>Команда</th>
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
            @foreach($goalieStats->items as $team)
                <tr>
                    <td>
                        @if($team->isActive)<i class="fas fa-users"></i>@endif
                    </td>
                    <td>
                        @if($team->id)
                            <a href="{{ route('team', ['team' => $team->id]) }}">{{ $team->name }}</a>
                        @else
                            {{ $team->name }}
                        @endif
                    </td>
                    <td class="text-right">{{ $team->games }}</td>
                    <td class="text-right">{{ $team->wins }}</td>
                    <td class="text-right">{{ $team->lose }}</td>
                    <td class="text-right">{{ $team->shot_against }}</td>
                    <td class="text-right">{{ $team->shot_against - $team->goals_against }}</td>
                    <td class="text-right">{{ $team->goals_against }}</td>
                    <td class="text-right">{{ round(1 - $team->goals_against / $team->shot_against, 3) }}</td>
                    <td class="text-right">{{ round($team->goals_against / $team->games, 2) }}</td>
                    <td class="text-right">{{ $team->shotouts }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-right">{{ $goalieStats->result->games }}</th>
                <th class="text-right">{{ $goalieStats->result->wins }}</th>
                <th class="text-right">{{ $goalieStats->result->lose }}</th>
                <th class="text-right">{{ $goalieStats->result->shot_against }}</th>
                <th class="text-right">{{ $goalieStats->result->shot_against - $team->goals_against }}</th>
                <th class="text-right">{{ $goalieStats->result->goals_against }}</th>
                <th class="text-right">
                    {{ round(1 - $goalieStats->result->goals_against / $goalieStats->result->shot_against, 3) }}
                </th>
                <th class="text-right">
                    {{ round($goalieStats->result->goals_against / $goalieStats->result->games, 2) }}
                </th>
                <th class="text-right">{{ $goalieStats->result->shotouts }}</th>
            </tr>
            </tfoot>
        </table>
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

    <script src="{!! mix('/js/amcharts.js') !!}"></script>
    <script type="text/javascript">
        am4core.ready(function () {

        });
    </script>
@endsection
