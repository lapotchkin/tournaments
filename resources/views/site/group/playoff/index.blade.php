@php
    /** @var \App\Models\GroupTournament $tournament */
    /** @var \App\Models\GroupTournamentPlayoff[][] $bracket */
@endphp

@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

    @if(count($tournament->teams) < 4)
        <div class="alert alert-danger">Недостаточно команд в турнире. Должно быть хотя бы 4.</div>
    @else
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
                                            <span class="badge rounded-pill bg-danger">&nbsp;</span>
                                            @if (!is_null($pair) && $pair->teamOne && $pair->teamOne->name)
                                                <a href="{{ route('team', ['team' => $pair->team_one_id]) }}">
                                                    @if ($winner === $pair->team_one_id)
                                                        <strong>{{$pair->teamOne->name }}</strong>
                                                    @else
                                                        {{$pair->teamOne->name }}
                                                    @endif
                                                </a>
                                                <span class="badge bg-success">{{ $pair->teamOne->short_name }}</span>
                                            @else
                                                ?
                                            @endif
                                        </div>
                                        <div class="col-2 text-end">
                                            @if (!is_null($pair) && $pair->teamOne && isset($seriesResult[$pair->teamOne->id]))
                                                @if ($winner === $pair->team_one_id)
                                                    <span class="badge rounded-pill bg-dark">
                                                    {{ $seriesResult[$pair->teamOne->id] }}
                                                </span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary">
                                                    {{ $seriesResult[$pair->teamOne->id] }}
                                                </span>
                                                @endif
                                            @else
                                                <span class="badge rounded-pill bg-secondary">0</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row pt-2 pb-2">
                                        <div class="col-12 text-center">
                                            @if(!is_null($pair))
                                                @foreach($pair->games as $game)
                                                    <a href="{{ route('group.tournament.playoff.game', ['groupTournament' => $tournament, 'groupTournamentPlayoff' => $pair, 'groupGamePlayoff' => $game]) }}"
                                                       class="btn btn-sm {{ $game->home_score > $game->away_score ? 'btn-danger' : 'btn-warning' }}">
                                                        {{ $game->home_score }}:{{ $game->away_score }}
                                                    </a>
                                                @endforeach
                                            @endif
                                            @can('update', $pair)
                                                <a href="{{ route('group.tournament.playoff.game.add', ['groupTournament' => $tournament, 'groupTournamentPlayoff' => $pair]) }}"
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10">
                                            <span class="badge rounded-pill bg-warning">&nbsp;</span>
                                            @if (!is_null($pair) && $pair->teamTwo && $pair->teamTwo->name)
                                                <a href="{{ route('team', ['team' => $pair->team_two_id]) }}">
                                                    @if ($winner === $pair->team_two_id)
                                                        <strong>{{$pair->teamTwo->name }}</strong>
                                                    @else
                                                        {{$pair->teamTwo->name }}
                                                    @endif
                                                </a>
                                                <span class="badge bg-success">{{ $pair->teamTwo->short_name }}</span>
                                            @else
                                                ?
                                            @endif
                                        </div>
                                        <div class="col-2 text-end">
                                            @if (!is_null($pair) && $pair->teamTwo && isset($seriesResult[$pair->teamTwo->id]))
                                                @if ($winner === $pair->team_two_id)
                                                    <span class="badge rounded-pill bg-dark">
                                                    {{ $seriesResult[$pair->teamTwo->id] }}
                                                </span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary">
                                                    {{ $seriesResult[$pair->teamTwo->id] }}
                                                </span>
                                                @endif
                                            @else
                                                <span class="badge rounded-pill bg-secondary">0</span>
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
    @endif
@endsection

@section('script')
    @parent
    <link href="{!! mix('/css/brackets.css') !!}" rel="stylesheet" type="text/css">
@endsection
