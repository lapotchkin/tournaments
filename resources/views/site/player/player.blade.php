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

    @if(count($player->tournaments))
        <h3 class="mt-3">Турниры 1 на 1</h3>
        <ul class="fa-ul">
            @foreach($player->tournaments as $tournament)
                <li>
                    <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                    <a href="{{ route('personal.tournament', ['tournamentId' => $tournament->id]) }}">{{ $tournament->title }}</a>
                    @foreach($tournament->winners as $winner)
                        @if($winner->player_id === $player->id)
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                            </span>
                        @endif
                    @endforeach
                </li>
            @endforeach
        </ul>
    @endif

    @if(count($teamStats->teams))
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
                <th class="text-right">ПОТ/И</th>
                <th class="text-right">СИЛ/И</th>
                <th class="text-right">ВБР</th>
                <th class="text-right">ШМИН/И</th>
                <th class="text-right">АТК</th>
                <th class="text-right">КОМ</th>
                <th class="text-right">ЗАЩ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teamStats->teams as $team)
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
                            {{ $team->plus_minus > 0 ? '+' . $team->plus_minus : str_replace('-', '−', $team->plus_minus) }}
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
                        {{ $team->rating_offense ? $team->giveaways_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->hits_per_game : '—' }}
                    </td>
                    <td class="text-right">
                        {{ $team->rating_offense ? $team->faceoff_win_percent . '%' : '—' }}
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
                        {{ $teamStats->result->plus_minus > 0 ? '+' . $teamStats->result->plus_minus : str_replace('-', '−', $teamStats->result->plus_minus) }}
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
                    {{ $teamStats->result->rating_offense ? $teamStats->result->giveaways_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->hits_per_game : '—' }}
                </th>
                <th class="text-right">
                    {{ $teamStats->result->rating_offense ? $teamStats->result->faceoff_win_percent . '%' : '—' }}
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
