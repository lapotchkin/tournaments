@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if (isset($game->tournament_id))
        {{ Breadcrumbs::render('group.tournament.regular.game', $game) }}
    @else
        {{ Breadcrumbs::render('group.tournament.playoff.game', $game) }}
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
                    <a href="{{ route('team', ['teamId' => $game->home_team_id]) }}">{{ $game->homeTeam->team->name }}</a>
                </h2>
                <h4>
                    <span class="badge badge-success">
                        {{ $game->homeTeam->team->short_name }}
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
                    <a href="{{ route('team', ['teamId' => $game->away_team_id]) }}">{{ $game->awayTeam->team->name }}</a>
                </h2>
                <h4>
                    <span class="badge badge-success">
                        {{ $game->awayTeam->team->short_name }}
                    </span>
                </h4>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-inline pb-3" style="justify-content:center">
        @if ($game->isOvertime)
            <span class="badge badge-pill badge-warning">Овертайм</span>
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

    @if (!$game->isTechnicalDefeat)
        <table class="table table-borderless table-sm mb-3 h5" style="width: 100%;">
            <tbody>
            <tr>
                <td class="text-right">{{ !is_null($game->home_shot) ? $game->home_shot : '—'}}</td>
                <th class="text-center" style="width:25%;">Всего бросков</th>
                <td class="text-left">{{ !is_null($game->away_shot) ? $game->away_shot : '—' }}</td>
            </tr>
            <tr>
                <td class="text-right">{{ !is_null($game->home_hit) ? $game->home_hit : '—' }}</td>
                <th class="text-center">Удары</th>
                <td class="text-left">{{ !is_null($game->away_hit) ? $game->away_hit : '—' }}</td>
            </tr>
            <tr>
                <td class="text-right">
                    {{ !is_null($game->home_attack_time) ? TextUtils::protocolTime($game->home_attack_time) : '—' }}
                </td>
                <th class="text-center">Время в атаке</th>
                <td class="text-left">
                    {{ !is_null($game->away_attack_time) ? TextUtils::protocolTime($game->away_attack_time) : '—' }}
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    {{ !is_null($game->home_pass_percent) ? str_replace('.', ',', $game->home_pass_percent) . '%' : '—' }}
                </td>
                <th class="text-center">Пас</th>
                <td class="text-left">
                    {{ !is_null($game->away_pass_percent) ? str_replace('.', ',', $game->away_pass_percent) . '%' : '—' }}
                </td>
            </tr>
            <tr>
                <td class="text-right">{{ !is_null($game->home_faceoff) ? $game->home_faceoff : '—' }}</td>
                <th class="text-center">Выигранные вбрасывания</th>
                <td class="text-left">{{ !is_null($game->away_faceoff) ? $game->away_faceoff : '—' }}</td>
            </tr>
            @if ($game->tournament->min_players === 6)
                <tr>
                    <td class="text-right">
                        {{ !is_null($game->home_penalty_time) ? TextUtils::protocolTime($game->home_penalty_time) : '—'}}
                    </td>
                    <th class="text-center">Штрафные минуты</th>
                    <td class="text-left">
                        {{ !is_null($game->away_penalty_time) ? TextUtils::protocolTime($game->away_penalty_time) : '—' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        {{ !is_null($game->home_penalty_success) ? $game->home_penalty_success : '—' }}
                        /
                        {{ !is_null($game->home_penalty_total) ? $game->home_penalty_total : '—' }}
                    </td>
                    <th class="text-center">Реализация большинства</th>
                    <td class="text-left">
                        {{ !is_null($game->away_penalty_success) ? $game->away_penalty_success : '—' }}
                        /
                        {{ !is_null($game->away_penalty_total) ? $game->away_penalty_total : '—' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        {{ !is_null($game->home_powerplay_time) ? TextUtils::protocolTime($game->home_powerplay_time) : '—' }}
                    </td>
                    <th class="text-center">Минут в большинстве</th>
                    <td class="text-left">
                        {{ !is_null($game->away_powerplay_time) ? TextUtils::protocolTime($game->away_powerplay_time) : '—' }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        {{ !is_null($game->home_shorthanded_goal) ? $game->home_shorthanded_goal : '—' }}
                    </td>
                    <th class="text-center">Голы в меньшинстве</th>
                    <td class="text-left">
                        {{ !is_null($game->away_shorthanded_goal) ? $game->away_shorthanded_goal : '—' }}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>

        <h3>{{ $game->homeTeam->team->name }}</h3>
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
            <tr>
                <th class="w-25">Игрок</th>
                <th class="text-center">ПОЗ</th>
                <th class="text-center">ГОЛ</th>
                <th class="text-center">ПАС</th>
                <th class="text-center">ОЧК</th>
                <th class="text-center">+/-</th>
                <th class="text-center">БР</th>
                <th class="text-center">БР%</th>
                <th class="text-center">СИЛ</th>
                <th class="text-center">ВБ%</th>
                <th class="text-center">ШМ</th>
                <th class="text-center">БЛК</th>
                <th class="text-center">ОТБ</th>
                <th class="text-center">ПОТ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($game->homeProtocols as $protocol)
                @if (!$protocol->isGoalie)
                    <tr>
                        <td>
                            <strong>
                                <a href="{{ route('player', ['playerId' => $protocol->player_id]) }}">{{ $protocol->player->name }}</a>
                            </strong>
                            <small>{{ $protocol->player->tag }}</small>
                        </td>
                        <td class="text-center">
                            {!! TextUtils::positionBadge($protocol->playerPosition) !!}
                        </td>
                        <td class="text-center">{{ $protocol->goals }}</td>
                        <td class="text-center">{{ $protocol->assists }}</td>
                        <td class="text-center">{{ $protocol->goals + $protocol->assists }}</td>
                        <td class="text-center">
                            {{ $protocol->plus_minus > 0 ? '+' . $protocol->plus_minus : $protocol->plus_minus }}
                        </td>
                        <td class="text-center">{{ $protocol->shots }}</td>
                        <td class="text-center">
                            {{ $protocol->shots ? round($protocol->goals / $protocol->shots * 100, 1) : '0,0' }}%
                        </td>
                        <td class="text-center">{{ $protocol->hits }}</td>
                        <td class="text-center">
                            {{ $protocol->faceoff_lose ? round($protocol->faceoff_win / ($protocol->faceoff_win + $protocol->faceoff_lose) * 100, 1) . '%' : '—' }}
                        </td>
                        <td class="text-center">{{ $protocol->penalty_minutes }}</td>
                        <td class="text-center">{{ $protocol->blocks }}</td>
                        <td class="text-center">{{ $protocol->takeaways }}</td>
                        <td class="text-center">{{ $protocol->giveaways }}</td>
                    </tr>
                @endIf
            @endforeach
            </tbody>
        </table>
        @if($game->homeGoalie)
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                <tr>
                    <th class="w-25">Игрок</th>
                    <th class="text-center">ПОЗ</th>
                    <th class="text-center">БР</th>
                    <th class="text-center">ОТБ</th>
                    <th class="text-center">ГОЛ</th>
                    <th class="text-center">КН</th>
                    <th class="text-center">1на1БР</th>
                    <th class="text-center">1на1ОТБ</th>
                    <th class="text-center">БУЛ</th>
                    <th class="text-center">БУЛОТБ</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <strong>
                            <a href="{{ route('player', ['playerId' => $game->homeGoalie->player_id]) }}">{{ $game->homeGoalie->player->name }}</a>
                        </strong>
                        <small>{{ $game->homeGoalie->player->tag }}</small>
                    </td>
                    <td class="text-center">
                        {!! TextUtils::positionBadge($game->homeGoalie->playerPosition) !!}
                    </td>
                    <td class="text-center">{{ $game->away_shot }}</td>
                    <td class="text-center">{{ $game->away_shot - $game->away_score }}</td>
                    <td class="text-center">{{ $game->away_score }}</td>
                    <td class="text-center">
                        {{ $game->away_shot ? round(($game->away_shot - $game->away_score) / ($game->away_shot), 3) : 0}}
                    </td>
                    <td class="text-center">{{ $game->homeGoalie->breakeaway_shots }}</td>
                    <td class="text-center">{{ $game->homeGoalie->breakeaway_saves }}</td>
                    <td class="text-center">{{ $game->homeGoalie->penalty_shots }}</td>
                    <td class="text-center">{{ $game->homeGoalie->penalty_saves }}</td>
                </tr>
                </tbody>
            </table>
        @endif
        <h3 class="mt-3">{{ $game->awayTeam->team->name }}</h3>
        <table class="table table-sm table-striped">
            <thead class="thead-dark">
            <tr>
                <th class="w-25">Игрок</th>
                <th class="text-center">ПОЗ</th>
                <th class="text-center">ГОЛ</th>
                <th class="text-center">ПАС</th>
                <th class="text-center">ОЧК</th>
                <th class="text-center">+/-</th>
                <th class="text-center">БР</th>
                <th class="text-center">БР%</th>
                <th class="text-center">СИЛ</th>
                <th class="text-center">ВБ%</th>
                <th class="text-center">ШМ</th>
                <th class="text-center">БЛК</th>
                <th class="text-center">ОТБ</th>
                <th class="text-center">ПОТ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($game->awayProtocols as $protocol)
                @if (!$protocol->isGoalie)
                    <tr>
                        <td>
                            <strong>
                                <a href="{{ route('player', ['playerId' => $protocol->player_id]) }}">{{ $protocol->player->name }}</a>
                            </strong>
                            <small>{{ $protocol->player->tag }}</small>
                        </td>
                        <td class="text-center">
                            {!! TextUtils::positionBadge($protocol->playerPosition) !!}
                        </td>
                        <td class="text-center">{{ $protocol->goals }}</td>
                        <td class="text-center">{{ $protocol->assists }}</td>
                        <td class="text-center">{{ $protocol->goals + $protocol->assists }}</td>
                        <td class="text-center">
                            {{ $protocol->plus_minus > 0 ? '+' . $protocol->plus_minus : $protocol->plus_minus }}
                        </td>
                        <td class="text-center">{{ $protocol->shots }}</td>
                        <td class="text-center">
                            {{ $protocol->shots ? round($protocol->goals / $protocol->shots * 100, 1) : '0,0' }}%
                        </td>
                        <td class="text-center">{{ $protocol->hits }}</td>
                        <td class="text-center">
                            {{ $protocol->faceoff_lose ? round($protocol->faceoff_win / ($protocol->faceoff_win + $protocol->faceoff_lose) * 100, 1) . '%' : '—' }}
                        </td>
                        <td class="text-center">{{ $protocol->penalty_minutes }}</td>
                        <td class="text-center">{{ $protocol->blocks }}</td>
                        <td class="text-center">{{ $protocol->takeaways }}</td>
                        <td class="text-center">{{ $protocol->giveaways }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        @if($game->awayGoalie)
            <table class="table table-sm table-striped">
                <thead class="thead-dark">
                <tr>
                    <th class="w-25">Игрок</th>
                    <th class="text-center">ПОЗ</th>
                    <th class="text-center">БР</th>
                    <th class="text-center">ОТБ</th>
                    <th class="text-center">ГОЛ</th>
                    <th class="text-center">КН</th>
                    <th class="text-center">1на1БР</th>
                    <th class="text-center">1на1ОТБ</th>
                    <th class="text-center">БУЛ</th>
                    <th class="text-center">БУЛОТБ</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <strong>
                            <a href="{{ route('player', ['playerId' => $game->awayGoalie->player_id]) }}">{{ $game->awayGoalie->player->name }}</a>
                        </strong>
                        <small>{{ $game->awayGoalie->player->tag }}</small>
                    </td>
                    <td class="text-center">
                        {!! TextUtils::positionBadge($game->awayGoalie->playerPosition) !!}
                    </td>
                    <td class="text-center">{{ $game->home_shot }}</td>
                    <td class="text-center">{{ $game->home_shot - $game->home_score }}</td>
                    <td class="text-center">{{ $game->home_score }}</td>
                    <td class="text-center">
                        {{ $game->home_shot ? round(($game->home_shot - $game->home_score) / ($game->home_shot), 3) : 0}}
                    </td>
                    <td class="text-center">{{ $game->awayGoalie->breakeaway_shots }}</td>
                    <td class="text-center">{{ $game->awayGoalie->breakeaway_saves }}</td>
                    <td class="text-center">{{ $game->awayGoalie->penalty_shots }}</td>
                    <td class="text-center">{{ $game->awayGoalie->penalty_saves }}</td>
                </tr>
                </tbody>
            </table>
        @endif
    @endif
@endsection
