@extends('layouts.site')

@section('title', $team->name . ' — ')

@section('content')
    {{ Breadcrumbs::render('team', $team) }}
    <h2>
        <i class="fab fa-{{ $team->platform->icon }} {{ $team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $team->name }}
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('team.edit', ['team' => $team->id]) }}">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
        @endauth
    </h2>

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
                <tr data-id="{{ $teamPlayer->player->id }}">
                    <td>
                        @switch($teamPlayer->isCaptain)
                            @case(1)<span class="badge badge-success">C</span>@break
                            @case(2)<span class="badge badge-warning">A</span>@break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('player', ['playerId' => $teamPlayer->player->id]) }}">{{ $teamPlayer->player->tag }}</a>
                        <small>{{ $teamPlayer->player->name }}</small>
                    </td>
                    <td class="text-right">
                        @can('create', 'App\Models\Team')
                            <div class="btn-group mr-2 captain-toggle" role="group" aria-label="First group">
                                <button type="button" data-captain="1"
                                        class="btn btn-primary btn-sm {{ $teamPlayer->isCaptain === 1 ? 'active' : '' }}">
                                    Капитан
                                </button>
                                <button type="button" type="button" data-captain="2"
                                        class="btn btn-primary btn-sm {{ $teamPlayer->isCaptain === 2 ? 'active' : '' }}">
                                    Заместитель
                                </button>
                                <button type="button" type="button" data-captain="0"
                                        class="btn btn-primary btn-sm {{ $teamPlayer->isCaptain === 0 ? 'active' : '' }}">
                                    Игрок
                                </button>
                            </div>
                        @endcan
                        @can('update', $team)
                            <button class="btn btn-danger btn-sm delete-player" data-id="{{ $teamPlayer->player->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
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

    @if($team->tournaments->count())
        <h3 class="mt-3">Командные турниры</h3>
        <ul class="fa-ul">
            @foreach($team->tournaments as $tournament)
                <li>
                    <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                    <a href="{{ route('group.tournament', ['tournamentId' => $tournament->id]) }}">{{ $tournament->title }}</a>
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

    @auth
        @if(Auth::user()->isAdmin())
            <script src="{!! mix('/js/teamManagerModule.js') !!}"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    const url = {
                        addPlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                        addPlayerRedirect: '{{ route('team', ['team' => $team->id])}}',
                        updatePlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                        deletePlayer: '{{ action('Ajax\TeamController@addPlayer', ['team' => $team->id])}}',
                    };

                    TRNMNT_playoffModule.init(url);
                });
            </script>
        @endif
    @endauth
@endsection
