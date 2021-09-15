@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.playoff', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalPlayoffMenu', ['tournament' => $tournament])

    <div class="tournament-bracket tournament-bracket--rounded pb-5">
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
                        <li class="tournament-bracket__item">
                            <div class="tournament-bracket__match" tabindex="0">
                                <div class="row">
                                    <div class="col-10">
                                        <span class="badge badge-pill badge-danger">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->playerOne && $pair->playerOne->tag)
                                            <a href="{{ route('player', ['player' => $pair->player_one_id]) }}">
                                                @if ($winner === $pair->player_one_id)
                                                    <strong>{{$pair->playerOne->tag }}</strong>
                                                @else
                                                    {{$pair->playerOne->tag }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success text-uppercase">
                                                {{ $pair->playerOne->getClubId($tournament->id) }}
                                            </span>
                                        @else
                                            ?
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
                                    <div class="col-12 text-center">
                                        @if(!is_null($pair))
                                            @foreach($pair->games as $game)
                                                <a href="{{ route('personal.tournament.playoff.game', ['personalTournament' => $tournament, 'personalTournamentPlayoff' => $pair, 'personalGamePlayoff' => $game]) }}"
                                                   class="btn btn-sm {{ $game->home_score > $game->away_score ? 'btn-danger' : 'btn-warning' }}">
                                                    {{ $game->home_score }}:{{ $game->away_score }}
                                                </a>
                                            @endforeach
                                        @endif
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10">
                                        <span class="badge badge-pill badge-warning">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->playerTwo && $pair->playerTwo->tag)
                                            <a href="{{ route('player', ['player' => $pair->player_two_id]) }}">
                                                @if ($winner === $pair->player_two_id)
                                                    <strong>{{$pair->playerTwo->tag }}</strong>
                                                @else
                                                    {{ $pair->playerTwo->tag }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success text-uppercase">
                                                {{ $pair->playerTwo->getClubId($tournament->id) }}
                                            </span>
                                        @else
                                            ?
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
    <div class="clearfix"></div>
@endsection

@section('script')
    @parent
    <link href="{!! mix('/css/brackets.css') !!}" rel="stylesheet" type="text/css">
@endsection
