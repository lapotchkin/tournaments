@extends('layouts.site')

@section('title', $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name . ' (Тур ' . $game->round . ') — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.game', $game) }}

    <h3 class="text-center">Тур {{ $game->round }}</h3>

    <table class="mb-2" style="width: 100%">
        <tbody>
        <tr>
            <td class="text-right" style="width:40%;">
                <h2>{{ $game->homeTeam->team->name }}</h2>
            </td>
            <td class="text-right" style="width:1rem;">
                <h2>
                    @if(!is_null($game->home_score))
                        <span class="badge badge-pill badge-primary">{{ $game->home_score}}</span>
                    @else
                        —
                    @endif
                </h2>
            </td>
            <td class="text-center" style="width:1rem;"><h2>:</h2></td>
            <td class="text-left" style="width:1rem;">
                <h2>
                    @if(!is_null($game->away_score))
                        <span class="badge badge-pill badge-primary">{{ $game->away_score}}</span>
                    @else
                        —
                    @endif
                </h2>
            </td>
            <td class="text-left" style="width:40%;">
                <h2>{{ $game->awayTeam->team->name }}</h2>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-inline mb-2" style="justify-content:center">
        @if ($game->isOvertime)
            <span class="badge badge-pill badge-warning">Овертайм</span>
        @endif
        @if ($game->isTechnicalDefeat)
            <span class="badge badge-pill badge-danger">Техническое поражение</span>
        @endif
    </div>
    @if ($game->playedAt)
        <div class="form-inline mb-2" style="justify-content:center">
            Дата игры: {{ (new \DateTime($game->playedAt))->format('d.m.Y') }}
        </div>
    @endif

    @if (!$game->isTechnicalDefeat)
        <table class="mt-3 mb-3" style="width: 100%">
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
                <th class="text-center">Выигранные вбрасывани</th>
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

        <h3 class="text-center">Статистика игроков</h3>
        <div class="row">
            <div class="col">
                <table class="table table-sm table-striped">
                    <thead>
                    <tr>
                        <th style="">Игрок</th>
                        <th style="width: 5rem;">Голы</th>
                        <th style="width: 5rem;">Пасы</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($game->homeProtocols as $protocol)
                        @if (!$protocol->isGoalie)
                            <tr>
                                <td>{{ $protocol->player->name }} ({{ $protocol->player->tag }})</td>
                                <td class="text-right">{{ $protocol->goals }}</td>
                                <td class="text-right">{{ $protocol->assists }}</td>
                            </tr>
                        @endIf
                    @endforeach
                    </tbody>
                </table>
                @if($game->homeGoalie)
                    <div>Вратарь: {{ $game->homeGoalie->player->name }} ({{ $game->homeGoalie->player->tag }})</div>
                @endif
            </div>
            <div class="col">
                <table class="table table-sm table-striped">
                    <thead>
                    <tr>
                        <th style="">Игрок</th>
                        <th style="width: 5rem;">Голы</th>
                        <th style="width: 5rem;">Пасы</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($game->awayProtocols as $protocol)
                        @if (!$protocol->isGoalie)
                            <tr>
                                <td>{{ $protocol->player->name }} ({{ $protocol->player->tag }})</td>
                                <td class="text-right">{{ $protocol->goals }}</td>
                                <td class="text-right">{{ $protocol->assists }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                @if($game->awayGoalie)
                    <div>Вратарь: {{ $game->awayGoalie->player->name }} ({{ $game->awayGoalie->player->tag }})</div>
                @endif
            </div>
        </div>
    @endif
@endsection
