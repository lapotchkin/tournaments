@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

    {{--    <main id="bracket">--}}
    {{--        @foreach ($bracket as $round => $pairs)--}}
    {{--            <ul class="round round-{{ $round }}">--}}
    {{--                <li class="spacer">&nbsp;</li>--}}

    {{--                @foreach ($pairs as $pair)--}}
    {{--                    @php--}}
    {{--                        $winner = $pair->getWinner();--}}
    {{--                        $seriesResult = $pair->getSeriesResult();--}}
    {{--                    @endphp--}}

    {{--                    <li class="game game-top {{ !is_null($pair) && $pair->team_one_id && $pair->team_one_id === $winner ? 'winner' : 'text-secondary' }}">--}}
    {{--                        @if (!is_null($pair) && $pair->teamOne && $pair->teamOne->name)--}}
    {{--                            <a href="{{ route('team', ['teamId' => $pair->team_one_id]) }}">--}}
    {{--                                {{$pair->teamOne->name }}--}}
    {{--                            </a>--}}
    {{--                        @else--}}
    {{--                            ?--}}
    {{--                        @endif--}}
    {{--                        <span>{{ !is_null($pair) && $pair->teamOne && isset($seriesResult[$pair->teamOne->id]) ? $seriesResult[$pair->teamOne->id] : 0 }}</span>--}}
    {{--                    </li>--}}
    {{--                    <li class="game game-spacer">&nbsp;</li>--}}
    {{--                    <li class="game game-bottom {{ !is_null($pair) && $pair->team_two_id && $pair->team_two_id === $winner ? 'winner' : 'text-secondary' }}">--}}
    {{--                        @if (!is_null($pair) && $pair->teamTwo && $pair->teamTwo->name)--}}
    {{--                            <a href="{{ route('team', ['teamId' => $pair->team_two_id]) }}">--}}
    {{--                                {{$pair->teamTwo->name }}--}}
    {{--                            </a>--}}
    {{--                        @else--}}
    {{--                            ?--}}
    {{--                        @endif--}}
    {{--                        <span>{{ !is_null($pair) && $pair->teamTwo && isset($seriesResult[$pair->teamTwo->id]) ? $seriesResult[$pair->teamTwo->id] : 0 }}</span>--}}
    {{--                    </li>--}}

    {{--                    <li class="spacer">&nbsp;</li>--}}
    {{--                @endforeach--}}
    {{--            </ul>--}}
    {{--        @endforeach--}}
    {{--    </main>--}}

    <div style="position: relative;">
        <div class="bracket">
            <section class="round quarterfinals">
                <div class="winners">
                    <div class="matchups">
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant winner"><span>Uno</span></div>
                                <div class="participant"><span>Ocho</span></div>
                            </div>
                        </div>
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant"><span>Dos</span></div>
                                <div class="participant winner"><span>Siete</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="connector">
                        <div class="merger"></div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="winners">
                    <div class="matchups">
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant"><span>Treis</span></div>
                                <div class="participant winner"><span>Seis</span></div>
                            </div>
                        </div>
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant"><span>Cuatro</span></div>
                                <div class="participant winner"><span>Cinco</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="connector">
                        <div class="merger"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </section>
            <section class="round semifinals">
                <div class="winners">
                    <div class="matchups">
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant winner"><span>Uno</span></div>
                                <div class="participant"><span>Dos</span></div>
                            </div>
                        </div>
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant winner"><span>Seis</span></div>
                                <div class="participant"><span>Cinco</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="connector">
                        <div class="merger"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </section>
            <section class="round finals">
                <div class="winners">
                    <div class="matchups">
                        <div class="matchup">
                            <div class="participants">
                                <div class="participant winner"><span>Uno</span></div>
                                <div class="participant"><span>Seis</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
