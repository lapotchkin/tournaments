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
                <th class="text-end">Победы</th>
                <th class="text-end">Проигрыши</th>
                <th class="text-end">Забито шайб</th>
                <th class="text-end">Пропущено шайб</th>
                <th class="text-end">Разница шайб</th>
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
                    <td class="text-end">{{ $stats->wins }}</td>
                    <td class="text-end">{{ $stats->lose }}</td>
                    <td class="text-end">{{ $stats->goals_for }}</td>
                    <td class="text-end">{{ $stats->goals_against }}</td>
                    <td class="text-end">
                        {{ ($stats->goals_for - $stats->goals_against) > 0 ? '+' : '' }}{{ $stats->goals_for - $stats->goals_against }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-end">{{ $personalStats->result->wins }}</th>
                <th class="text-end">{{ $personalStats->result->lose }}</th>
                <th class="text-end">{{ $personalStats->result->goals_for }}</th>
                <th class="text-end">{{ $personalStats->result->goals_against }}</th>
                <th class="text-end">{{ ($personalStats->result->goals_for - $personalStats->result->goals_against) > 0 ? '+' : '' }}{{ $personalStats->result->goals_for - $personalStats->result->goals_against }}</th>
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
                <th class="text-end">ИГР</th>
                <th class="text-end">ОЧК</th>
                <th class="text-end">ГОЛ</th>
                <th class="text-end">ПЕР</th>
                <th class="text-end">+/-</th>
                <th class="text-end">БРОС/И</th>
                <th class="text-end">ОТБ/И</th>
                <th class="text-end">ПЕР/И</th>
                <th class="text-end">ПОТ/И</th>
                <th class="text-end">СИЛ/И</th>
                <th class="text-end">ВБР</th>
                <th class="text-end">ПАС</th>
                <th class="text-end">ШМИН/И</th>
                <th class="text-end">АТК</th>
                <th class="text-end">КОМ</th>
                <th class="text-end">ЗАЩ</th>
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
                    <td class="text-end">{{ $team->games }}</td>
                    <td class="text-end">{{ $team->points }}</td>
                    <td class="text-end">{{ $team->goals }}</td>
                    <td class="text-end">{{ $team->assists }}</td>
                    <td class="text-end">
                        @if($team->rating_offense)
                            {{ $team->plus_minus > 0 ? '+' . $team->plus_minus : $team->plus_minus }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->shots_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->takeaways_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->interceptions_per_game ? $team->interceptions_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->giveaways_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->hits_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->faceoff_win_percent . '%' : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->interceptions_per_game ? $team->pass_percent . '%' : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->penalty_minutes_per_game : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_offense ? $team->rating_offense . '%' : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_teamplay ? $team->rating_teamplay . '%' : '—' }}
                    </td>
                    <td class="text-end">
                        {{ $team->rating_defense ? $team->rating_defense . '%' : '—' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-end">{{ $teamStats->result->games }}</th>
                <th class="text-end">{{ $teamStats->result->points }}</th>
                <th class="text-end">{{ $teamStats->result->goals }}</th>
                <th class="text-end">{{ $teamStats->result->assists }}</th>
                <th class="text-end">
                    @if($teamStats->result->rating_offense)
                        {{ $teamStats->result->plus_minus > 0 ? '+' . $teamStats->result->plus_minus : $teamStats->result->plus_minus }}
                    @else
                        —
                    @endif
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->shots_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->takeaways_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->interceptions_per_game ? $teamStats->result->interceptions_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->giveaways_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->hits_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->faceoff_win_percent . '%' : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->interceptions_per_game ? $teamStats->result->pass_percent . '%' : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->penalty_minutes_per_game : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->rating_offense . '%' : '—' }}
                </th>
                <th class="text-end">
                    {{ $teamStats->result->rating_teamplay ? $teamStats->result->rating_teamplay . '%' : '—' }}
                </th>
                <th class="text-end">
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
                <th class="text-end">ИГР</th>
                <th class="text-end">ПОБ</th>
                <th class="text-end">ПОР</th>
                <th class="text-end">БРОС</th>
                <th class="text-end">ОТБ</th>
                <th class="text-end">ГОЛ</th>
                <th class="text-end">%ОТБ</th>
                <th class="text-end">ГОЛ/И</th>
                <th class="text-end">СУХ</th>
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
                    <td class="text-end">{{ $team->games }}</td>
                    <td class="text-end">{{ $team->wins }}</td>
                    <td class="text-end">{{ $team->lose }}</td>
                    <td class="text-end">{{ $team->shot_against }}</td>
                    <td class="text-end">{{ $team->shot_against - $team->goals_against }}</td>
                    <td class="text-end">{{ $team->goals_against }}</td>
                    <td class="text-end">{{ round(1 - $team->goals_against / $team->shot_against, 3) }}</td>
                    <td class="text-end">{{ round($team->goals_against / $team->games, 2) }}</td>
                    <td class="text-end">{{ $team->shotouts }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>ВСЕГО:</th>
                <th class="text-end">{{ $goalieStats->result->games }}</th>
                <th class="text-end">{{ $goalieStats->result->wins }}</th>
                <th class="text-end">{{ $goalieStats->result->lose }}</th>
                <th class="text-end">{{ $goalieStats->result->shot_against }}</th>
                <th class="text-end">{{ $goalieStats->result->shot_against - $team->goals_against }}</th>
                <th class="text-end">{{ $goalieStats->result->goals_against }}</th>
                <th class="text-end">
                    {{ round(1 - $goalieStats->result->goals_against / $goalieStats->result->shot_against, 3) }}
                </th>
                <th class="text-end">
                    {{ round($goalieStats->result->goals_against / $goalieStats->result->games, 2) }}
                </th>
                <th class="text-end">{{ $goalieStats->result->shotouts }}</th>
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
