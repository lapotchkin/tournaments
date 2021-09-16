@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (Расписание) — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.regular.games', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalRegularMenu', ['tournament' => $tournament])

    <div class="mb-3 form-inline">
        <select id="playersList" class="form-select mt-3 mr-2" name="players">
            <option value="0">Все игроки</option>
            @foreach($tournament->players as $player)
                <option value="{{ $player->tag }}">
                    {{ $player->tag }}
                    {{ $player->name ? '(' . $player->name . ')' : '' }}
                </option>
            @endforeach
        </select>
        <select id="roundsList" class="form-select mt-3 mr-2" name="rounds">
            <option value="0">Все туры</option>
            @foreach(array_keys($rounds) as $round)
                <option value="round{{ $round }}">Тур {{ $round }}</option>
            @endforeach
        </select>
        @if(count($divisions) > 1)
            <select id="divisionsList" class="form-select mt-3" name="divisions">
                <option value="0">Все группы</option>
                @foreach($divisions as $division)
                    <option value="division{{ $division }}">Группа {{ TextUtils::divisionLetter($division) }}</option>
                @endforeach
            </select>
        @endif
    </div>

    @foreach($rounds as $round => $divisions)
        <div class="rounds" id="round{{ $round }}">
            <h3>Тур {{ $round }}</h3>
            <div class="row">
                @foreach($divisions as $division => $games)
                    <div class="col-12 divisions division{{ $division }}">
                        @if (count($divisions) > 1)
                            <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
                        @endif
                        <table class="table table-striped table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width: 2em;"></th>
                                <th style="width: 6em;">Дата игры</th>
                                <th class="text-end">Хозяева</th>
                                <th class="text-end" style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                                <th style="width: 1em;">:</th>
                                <th style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                                <th class="text-start">Гости</th>
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
                                        class="badge rounded-pill bg-warning">{{ $game->isOvertime ? 'О' : '' }}</span>
                                        <span
                                            class="badge rounded-pill bg-dark">{{ $game->isShootout ? 'Б' : '' }}</span>
                                        <span
                                            class="badge rounded-pill bg-danger">{{ $game->isTechnicalDefeat ? 'T' : '' }}</span>
                                    </td>
                                    <td>
                                        {{ $game->playedAt ? (new \DateTime($game->playedAt))->format('d.m.Y') : '' }}
                                    </td>
                                    <td class="text-end">
                                        @if ($game->home_score > $game->away_score)
                                            <strong><a
                                                    href="{{ route('player', ['player' => $game->home_player_id]) }}">{{ $game->homePlayer->tag }}</a></strong>
                                        @else
                                            <a href="{{ route('player', ['player' => $game->home_player_id]) }}">{{ $game->homePlayer->tag }}</a>
                                        @endif
                                        <small>{{ $game->homePlayer->name }}</small>
                                        <span class="badge bg-success text-uppercase">
                                        {{ $game->homePlayer->getClubId($game->tournament_id) }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        {!! !is_null($game->home_score) ? '<span class="badge bg-dark rounded-pill">' . $game->home_score . '</span>' : '—' !!}
                                    </td>
                                    <td class="text-center">:</td>
                                    <td class="text-start">
                                        {!! !is_null($game->away_score) ? '<span class="badge bg-dark rounded-pill">' . $game->away_score . '</span>' : '—' !!}
                                    </td>
                                    <td class="text-start">
                                    <span class="badge bg-success text-uppercase">
                                        {{ $game->awayPlayer->getClubId($game->tournament_id) }}
                                    </span>
                                        @if ($game->home_score < $game->away_score)
                                            <strong><a
                                                    href="{{ route('player', ['player' => $game->away_player_id]) }}">{{ $game->awayPlayer->tag }}</a></strong>
                                        @else
                                            <a href="{{ route('player', ['player' => $game->away_player_id]) }}">{{ $game->awayPlayer->tag }}</a>
                                        @endif
                                        <small>{{ $game->awayPlayer->name }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('personal.tournament.regular.game', ['personalTournament' => $tournament, 'personalGameRegular' => $game]) }}">
                                            <i class="fas fa-gamepad"></i> протокол
                                        </a>
                                    </td>
                                    @auth
                                        @if(Auth::user()->isAdmin())
                                            <td class="text-end">
                                                <a href="{{ route('personal.tournament.regular.game.edit', ['personalTournament' => $tournament, 'personalGameRegular' => $game]) }}"
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
        </div>
    @endforeach
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            const $playerList = $('#playersList');
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

            const $roundsList = $('#roundsList');
            $roundsList.on('change', function () {
                const $rows = $('.rounds');
                const val = $(this).val();
                if (val === '0') {
                    $rows.show();
                    return;
                }

                $rows.hide();
                $('#' + $(this).val()).show();
            });

                @if(count($divisions) > 1)
            const $divisionsList = $('#divisionsList');
            $divisionsList.on('change', function () {
                const $rows = $('.divisions');
                const val = $(this).val();
                if (val === '0') {
                    $rows.show();
                    return;
                }

                $rows.hide();
                $('.' + $(this).val()).show();
            });
            @endif

            if (window.location.hash) {
                $playerList
                    .val(window.location.hash.replace('#', ''))
                    .trigger('change');
            }
        });
    </script>
@endsection
