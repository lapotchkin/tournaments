@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

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
                            {{ !is_null($pair) ? 'data-id="' . $pair->id . '"' : '' }}>
                            <div class="tournament-bracket__match" tabindex="0">
                                <div class="row">
                                    <div class="col-10 form-inline">
                                        <span class="badge badge-pill badge-danger mr-2">&nbsp;</span>
                                        <select class="form-control form-control-sm" name="team_one_id">
                                            <option value="">--</option>
                                            @foreach($tournament->tournamentTeams as $tournamentTeam)
                                                <option value="{{ $tournamentTeam->team_id }}"
                                                    {{ !is_null($pair) && $pair->team_one_id === $tournamentTeam->team_id ? 'selected' : '' }}>
                                                    {{ $tournamentTeam->team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 text-right">
                                        @if (!is_null($pair) && $pair->teamOne && isset($seriesResult[$pair->teamOne->id]))
                                            @if ($winner === $pair->team_one_id)
                                                <span class="badge badge-pill badge-dark">
                                                    {{ $seriesResult[$pair->teamOne->id] }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary">
                                                    {{ $seriesResult[$pair->teamOne->id] }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge badge-pill badge-secondary">0</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row pt-2 pb-2">
                                    <div class="col-10 text-center">
                                        @if(!is_null($pair))
                                            @foreach($pair->games as $game)
                                                <a href="{{ route('group.tournament.playoff.game.edit', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}"
                                                   class="btn btn-sm {{ $game->home_score > $game->away_score ? 'btn-danger' : 'btn-warning' }}">
                                                    {{ $game->home_score }}:{{ $game->away_score }}
                                                </a>
                                            @endforeach
                                        @endif
                                        <a href="{{ route('group.tournament.playoff.game.add', ['tournamentId' => $tournament->id]) }}" class="btn btn-sm btn-success" {!! !is_null($pair) ? '' : 'style="display:none;"' !!}>
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 text-right">
                                        <button type="button" class="btn btn-sm btn-primary savePair">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10 form-inline">
                                        <span class="badge badge-pill badge-warning mr-2">&nbsp;</span>
                                        <select class="form-control form-control-sm" name="team_two_id">
                                            <option value="">--</option>
                                            @foreach($tournament->tournamentTeams as $tournamentTeam)
                                                <option value="{{ $tournamentTeam->team_id }}"
                                                    {{ !is_null($pair) && $pair->team_two_id === $tournamentTeam->team_id ? 'selected' : '' }}>
                                                    {{ $tournamentTeam->team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 text-right">
                                        @if (!is_null($pair) && $pair->teamTwo && isset($seriesResult[$pair->teamTwo->id]))
                                            @if ($winner === $pair->team_two_id)
                                                <span class="badge badge-pill badge-dark">
                                                    {{ $seriesResult[$pair->teamTwo->id] }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary">
                                                    {{ $seriesResult[$pair->teamTwo->id] }}
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
            TRNMNT_playoffModule.init({
                createPair: '{{ action('Ajax\GroupController@savePair', ['tournamentId' => $tournament->id]) }}'
            });
        });
    </script>
@endsection
