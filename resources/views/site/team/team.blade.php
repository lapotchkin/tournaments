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
@endsection
