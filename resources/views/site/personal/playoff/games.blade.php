@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.playoff.games', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalPlayoffMenu', ['tournament' => $tournament])

    <div class="tournament-bracket tournament-bracket--rounded">
        @foreach ($bracket as $round => $pairs)
            <div
                class="tournament-bracket__round {{ $tournament->thirdPlaceSeries ? TextUtils::playoffClass($loop->iteration, count($bracket)) : '' }}">
                <h4>{{ TextUtils::playoffRound($tournament, $round) }}</h4>
                <ul class="tournament-bracket__list">
                    @foreach ($pairs as $pair)
                        @php
                            $winner = !is_null($pair) ? $pair->getWinner() : '';
                            $seriesResult = !is_null($pair) ? $pair->getSeriesResult() : null;
                        @endphp
                        <li class="tournament-bracket__item"
                            {!! !is_null($pair) ? 'data-id="' . $pair->id . '"' : '' !!}>
                            <div class="tournament-bracket__match" tabindex="0">
                                <div class="row">
                                    <div class="col-10 form-inline">
                                        <span class="badge badge-pill badge-danger mr-2">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->playerOne && count($pair->games))
                                            <a href="{{ route('player', ['player' => $pair->player_one_id]) }}">
                                                @if ($winner === $pair->player_one_id)
                                                    <strong>{{$pair->playerOne->name }}</strong>
                                                @else
                                                    {{$pair->playerOne->name }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success text-uppercase ml-1">
                                                {{ $pair->playerOne->getClubId($tournament->id) }}
                                            </span>
                                        @else
                                            <select class="form-control form-control-sm" name="player_one_id">
                                                <option value="">--</option>
                                                @foreach($tournament->tournamentPlayers as $tournamentPlayer)
                                                    <option value="{{ $tournamentPlayer->player_id }}"
                                                        {{ !is_null($pair) && $pair->player_one_id === $tournamentPlayer->player_id ? 'selected' : '' }}>
                                                        {{ $tournamentPlayer->player->tag }}
                                                        {{ $tournamentPlayer->player->name ? '(' . $tournamentPlayer->player->name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-2 text-right">
                                        @if (!is_null($pair) && $pair->playerOne && isset($seriesResult[$pair->playerOne->id]))
                                            @if ($winner === $pair->player_one_id)
                                                <span class="badge badge-pill badge-dark">
                                                    {{ $seriesResult[$pair->playerOne->id] }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary">
                                                    {{ $seriesResult[$pair->playerOne->id] }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge badge-pill badge-secondary">0</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row pt-2 pb-2">
                                    <div class="col-9 text-center">
                                        @if(!is_null($pair))
                                            @foreach($pair->games as $game)
                                                <a href="{{ route('personal.tournament.playoff.game.edit', ['personalTournament' => $tournament, 'personalTournamentPlayoff' => $pair, 'personalGamePlayoff' => $game]) }}"
                                                   class="btn btn-sm {{ $game->home_score > $game->away_score ? 'btn-danger' : 'btn-warning' }}">
                                                    {{ $game->home_score }}:{{ $game->away_score }}
                                                </a>
                                            @endforeach
                                            <a href="{{ route('personal.tournament.playoff.game.add', ['personalTournament' => $tournament, 'personalTournamentPlayoff' => $pair]) }}"
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('personal.tournament.playoff.games', ['personalTournament' => $tournament]) }}"
                                               class="btn btn-sm btn-success addGame" style="display:none;">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        @endif
                                    </div>
                                    @if (is_null($pair) || !count($pair->games))
                                        <div class="col-3 text-right align-middle">
                                            <button type="button" class="btn btn-sm btn-primary savePair">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-10 form-inline">
                                        <span class="badge badge-pill badge-warning mr-2">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->playerTwo && count($pair->games))
                                            <a href="{{ route('player', ['player' => $pair->player_two_id]) }}">
                                                @if ($winner === $pair->player_two_id)
                                                    <strong>{{$pair->playerTwo->name }}</strong>
                                                @else
                                                    {{$pair->playerTwo->name }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success text-uppercase ml-1">
                                                {{ $pair->playerTwo->getClubId($tournament->id) }}
                                            </span>
                                        @else
                                            <select class="form-control form-control-sm" name="player_two_id">
                                                <option value="">--</option>
                                                @foreach($tournament->tournamentPlayers as $tournamentPlayer)
                                                    <option value="{{ $tournamentPlayer->player_id }}"
                                                        {{ !is_null($pair) && $pair->player_two_id === $tournamentPlayer->player_id ? 'selected' : '' }}>
                                                        {{ $tournamentPlayer->player->tag }}
                                                        {{ $tournamentPlayer->player->name ? '(' . $tournamentPlayer->player->name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-2 text-right">
                                        @if (!is_null($pair) && $pair->playerTwo && isset($seriesResult[$pair->playerTwo->id]))
                                            @if ($winner === $pair->player_two_id)
                                                <span class="badge badge-pill badge-dark">
                                                    {{ $seriesResult[$pair->playerTwo->id] }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary">
                                                    {{ $seriesResult[$pair->playerTwo->id] }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge badge-pill badge-secondary">0</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endsection

@section('script')
    @parent
    <link href="{!! mix('/css/brackets.css') !!}" rel="stylesheet" type="text/css">
    <script src="{!! mix('/js/playoffModule.js') !!}"></script>
    <script>
        $(document).ready(function () {
            TRNMNT_playoffModule.init(
                {
                    createPair: '{{ action('Ajax\PersonalController@createPair', ['personalTournament' => $tournament]) }}',
                },
                'player'
            );
        });
    </script>
@endsection
