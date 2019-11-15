@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if (isset($game->tournament_id))
        {{ Breadcrumbs::render('personal.tournament.regular.game', $game) }}
    @else
        {{ Breadcrumbs::render('personal.tournament.playoff.game', $game) }}
    @endif

    <h4 class="text-center">
        @if ($game->playoffPair)
            {{ TextUtils::playoffRound($game->tournament, $game->playoffPair->round) }}
        @else
            Тур {{ $game->round }}
        @endif
    </h4>

    <table class="w-100">
        <tbody>
        <tr>
            <td class="text-right" style="width:40%;">
                <h2>
                    <a href="{{ route('player', ['player' => $game->home_player_id]) }}">{{ $game->homePlayer->tag }}</a>
                </h2>
                <h4>
                    {{ $game->homePlayer->name }}
                    <span class="badge badge-success text-uppercase">
                        {{ $game->homePlayer->getClubId($game->playoffPair ? $game->playoffPair->tournament_id : $game->tournament_id) }}
                    </span>
                </h4>
            </td>
            <td class="text-right" style="width:1rem;">
                <h1>
                    @if(!is_null($game->home_score))
                        <span class="badge badge-pill badge-dark">{{ $game->home_score}}</span>
                    @else
                        —
                    @endif
                </h1>
            </td>
            <td class="text-center" style="width:1rem;"><h2>:</h2></td>
            <td class="text-left" style="width:1rem;">
                <h1>
                    @if(!is_null($game->away_score))
                        <span class="badge badge-pill badge-dark">{{ $game->away_score}}</span>
                    @else
                        —
                    @endif
                </h1>
            </td>
            <td class="text-left" style="width:40%;">
                <h2>
                    <a href="{{ route('player', ['player' => $game->away_player_id]) }}">{{ $game->awayPlayer->tag }}</a>
                </h2>
                <h4>
                    <span class="badge badge-success text-uppercase">
                        {{ $game->awayPlayer->getClubId($game->playoffPair ? $game->playoffPair->tournament_id : $game->tournament_id) }}
                    </span>
                    {{ $game->awayPlayer->name }}
                </h4>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-inline pb-3" style="justify-content:center">
        @if ($game->isOvertime)
            <span class="badge badge-pill badge-warning">Овертайм</span>
        @endif
        @if ($game->isShootout)
            <span class="badge badge-pill badge-dark">Буллиты</span>
        @endif
        @if ($game->isTechnicalDefeat)
            <span class="badge badge-pill badge-danger">Техническое поражение</span>
        @endif
    </div>
    @if ($game->playedAt)
        <div class="form-inline pb-3" style="justify-content:center">
            Дата игры: {{ (new \DateTime($game->playedAt))->format('d.m.Y') }}
        </div>
    @endif
@endsection
