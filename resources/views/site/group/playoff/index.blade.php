@extends('layouts.site')

@section('title', $tournament->title . ': Плей-офф — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupPlayoffMenu', ['tournament' => $tournament])

    <div class="tournament-bracket tournament-bracket--rounded">
        @foreach ($bracket as $round => $pairs)
            <div class="tournament-bracket__round w-25">
                <h4>
                    @switch($maxTeams / pow(2, $round))
                        @case(8) ⅛ финала @break
                        @case(4) ¼ финала @break
                        @case(2) ½ финала @break
                        @default Финал
                    @endswitch
                </h4>
                <ul class="tournament-bracket__list">
                    @foreach ($pairs as $pair)
                        @php
                            $winner = !is_null($pair) ? $pair->getWinner() : '';
                            $seriesResult = !is_null($pair) ? $pair->getSeriesResult() : null;
                        @endphp
                        <li class="tournament-bracket__item">
                            <div class="tournament-bracket__match" tabindex="0">
                                <div class="row">
                                    <div class="col-9">
                                        <span class="badge badge-pill badge-danger">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->teamOne && $pair->teamOne->name)
                                            <a href="{{ route('team', ['teamId' => $pair->team_one_id]) }}">
                                                @if ($winner === $pair->team_one_id)
                                                    <strong>{{$pair->teamOne->name }}</strong>
                                                @else
                                                    {{$pair->teamOne->name }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success">{{ $pair->teamOne->short_name }}</span>
                                        @else
                                            ?
                                        @endif
                                    </div>
                                    <div class="col-3 text-right">
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
                                    <div class="col-12 text-center">
                                        @if(!is_null($pair))
                                            @foreach($pair->games as $game)
                                                <a href="{{ route('group.tournament.playoff.game', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}"
                                                    class="badge {{ $game->home_score > $game->away_score ? 'badge-danger' : 'badge-warning' }}">
                                                    {{ $game->home_score }} : {{ $game->away_score }}
                                                </a>
                                            @endforeach
                                        @endif
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9">
                                        <span class="badge badge-pill badge-warning">&nbsp;</span>
                                        @if (!is_null($pair) && $pair->teamTwo && $pair->teamTwo->name)
                                            <a href="{{ route('team', ['teamId' => $pair->team_two_id]) }}">
                                                @if ($winner === $pair->team_two_id)
                                                    <strong>{{$pair->teamTwo->name }}</strong>
                                                @else
                                                    {{$pair->teamTwo->name }}
                                                @endif
                                            </a>
                                            <span class="badge badge-success">{{ $pair->teamTwo->short_name }}</span>
                                        @else
                                            ?
                                        @endif
                                    </div>
                                    <div class="col-3 text-right">
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


    <!--
    <div class="tournament-bracket tournament-bracket--rounded">
        <div class="tournament-bracket__round">
            <h4>Quarterfinals</h4>
            <ul class="tournament-bracket__list">
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Canada">CAN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-ca"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Kazakhstan">KAZ</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-kz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Sweden">SVE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-se"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Sweden">SVE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-se"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Sweden">SVE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-se"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Russia">RUS</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-ru"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Belarus">BEL</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-by"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tournament-bracket__round">
            <h4>Semifinals</h4>
            <ul class="tournament-bracket__list">
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Canada">CAN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-ca"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Sweden">SVE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-se"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>

                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Russia">RUS</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-ru"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">7</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tournament-bracket__round">
            <h4>Bronze medal game</h4>
            <ul class="tournament-bracket__list">
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Finland">FIN</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-fi"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">2</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Sweden">SVE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-se"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tournament-bracket__round">
            <h4>Gold medal game</h4>
            <ul class="tournament-bracket__list">
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tournament-bracket__round tournament-bracket__with_third_place">
            <h4>Gold medal game</h4>
            <ul class="tournament-bracket__list">
                <li class="tournament-bracket__item">
                    <div class="tournament-bracket__match" tabindex="0">
                        <table class="tournament-bracket__table">
                            <tbody class="tournament-bracket__content">
                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code" title="Czech Republic">CZE</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-cz"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">4</span>
                                </td>
                            </tr>
                            <tr class="tournament-bracket__team">
                                <td class="tournament-bracket__country">
                                    <abbr class="tournament-bracket__code"
                                          title="Unitede states of America">USA</abbr>
                                    <span class="tournament-bracket__flag flag-icon flag-icon-us"
                                          aria-label="Flag"></span>
                                </td>
                                <td class="tournament-bracket__score">
                                    <span class="tournament-bracket__number">1</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    -->
@endsection

@section('script')
    @parent
    <link href="{!! mix('/css/brackets.css') !!}" rel="stylesheet" type="text/css">
@endsection
