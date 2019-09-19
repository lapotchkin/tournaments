@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (Расписание) — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.regular.games', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalRegularMenu', ['tournament' => $tournament])

    <div class="mb-3 w-25">
        <select id="playersList" class="form-control mt-3" name="players">
            <option value="0">Все</option>
            @foreach($tournament->players as $player)
                <option value="{{ $player->name }}">{{ $player->tag }} ({{ $player->name }})</option>
            @endforeach
        </select>
    </div>

    @foreach($rounds as $round => $divisions)
        <h3>Тур {{ $round }}</h3>
        <div class="row">
            @foreach($divisions as $division => $games)
                <div class="col-12">
                    @if (count($divisions) > 1)
                        <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
                    @endif
                    <table class="table table-striped table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th style="width: 2em;"></th>
                            <th style="width: 6em;">Дата игры</th>
                            <th class="text-right">Хозяева</th>
                            <th class="text-right" style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                            <th style="width: 1em;">:</th>
                            <th style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                            <th class="text-left">Гости</th>
                            <th style="width: 8em;"></th>
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <th style="width: 2em;"></th>
                                @endif
                            @endauth
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($games as $game)
                            <tr class="games {{ !is_null($game->home_score) ? 'table-success' : '' }}">
                                <td>
                                    <span
                                        class="badge badge-pill badge-warning">{{ $game->isOvertime ? 'О' : '' }}</span>
                                    <span class="badge badge-pill badge-dark">{{ $game->isShootout ? 'Б' : '' }}</span>
                                    <span
                                        class="badge badge-pill badge-danger">{{ $game->isTechnicalDefeat ? 'T' : '' }}</span>
                                </td>
                                <td>
                                    {{ $game->playedAt ? (new \DateTime($game->playedAt))->format('d.m.Y') : '' }}
                                </td>
                                <td class="text-right">
                                    @if ($game->home_score > $game->away_score)
                                        <strong><a
                                                href="{{ route('player', ['playerId' => $game->home_player_id]) }}">{{ $game->homePlayer->name }}</a></strong>
                                    @else
                                        <a href="{{ route('player', ['playerId' => $game->home_player_id]) }}">{{ $game->homePlayer->name }}</a>
                                    @endif
                                    <small>{{ $game->homePlayer->tag }}</small>
                                    <span class="badge badge-success text-uppercase">
                                        {{ $game->homePlayer->getClubId($game->tournament_id) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {!! !is_null($game->home_score) ? '<span class="badge badge-dark badge-pill">' . $game->home_score . '</span>' : '—' !!}
                                </td>
                                <td class="text-center">:</td>
                                <td class="text-left">
                                    {!! !is_null($game->away_score) ? '<span class="badge badge-dark badge-pill">' . $game->away_score . '</span>' : '—' !!}
                                </td>
                                <td class="text-left">
                                    <span class="badge badge-success text-uppercase">
                                        {{ $game->awayPlayer->getClubId($game->tournament_id) }}
                                    </span>
                                    @if ($game->home_score < $game->away_score)
                                        <strong><a
                                                href="{{ route('player', ['playerId' => $game->away_player_id]) }}">{{ $game->awayPlayer->name }}</a></strong>
                                    @else
                                        <a href="{{ route('player', ['playerId' => $game->away_player_id]) }}">{{ $game->awayPlayer->name }}</a>
                                    @endif
                                    <small>{{ $game->awayPlayer->tag }}</small>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-primary"
                                       href="{{ route('personal.tournament.regular.game', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}">
                                        <i class="fas fa-gamepad"></i> протокол
                                    </a>
                                </td>
                                @auth
                                    @if(Auth::user()->isAdmin())
                                        <td class="text-right">
                                            <a href="{{ route('personal.tournament.regular.game.edit', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}"
                                               class="btn btn-sm btn-danger"><i class="fas fa-edit"></i></a>
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            var $playerList = $('#playersList');

            $playerList.change(function () {
                console.log('fired');
                const $rows = $('.games');
                $rows.show();
                if (this.value === '0') {
                    window.location.hash = '';
                    return;
                }
                window.location.hash = '#' + this.value;
                $rows.not(':contains("' + this.value + '")').hide();
            });

            console.log(window.location.hash);

            if (window.location.hash) {
                $playerList
                    .val(window.location.hash.replace('#', ''))
                    .trigger('change');
            }
        });
    </script>
@endsection
