@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if ($pair)
        {{ Breadcrumbs::render('group.tournament.playoff.game.add', $pair) }}
    @else
        {{ Breadcrumbs::render('group.tournament.regular.game', $game) }}
    @endif

    <h3 class="text-center">
        @if ($pair)
            {{ TextUtils::playoffRound($pair->tournament, $pair->round) }}
        @else
            Тур {{ $game->round }}
        @endif
    </h3>
    @if($game && !is_null($game->tournament->vk_group_id))
        <div class="text-center mb-3">
            <button type="button" class="btn btn-info" id="share">
                <i class="fab fa-vk"></i> Выложить результат в ВК
            </button>
        </div>
    @endif
    <form id="game-form">
        @if(!Auth::user()->isAdmin())
            <input type="hidden" id="redirectToProtocol" name="redirectToProtocol" value="true">
        @endif
        <table class="mb-2 w-100">
            <tbody>
            <tr>
                <td class="text-right pr-3 w-50">
                    <h2>{{ $pair ? $pair->teamOne->name : $game->homeTeam->team->name }}</h2>
                </td>
                <td style="width:1rem;"></td>
                <td class="text-left pl-3 w-50">
                    <h2>{{ $pair ? $pair->teamTwo->name : $game->awayTeam->team->name }}</h2>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    <input
                            style="width: 6rem;"
                            type="number"
                            id="home_score"
                            class="form-control form-control-lg text-center float-right"
                            name="home_score"
                            value="{{ $game ? $game->home_score : '' }}">
                </td>
                <td class="text-center"><h2>:</h2></td>
                <td class="text-left">
                    <input
                            style="width: 6rem;"
                            type="number"
                            id="away_score"
                            class="form-control form-control-lg text-center float-left"
                            name="away_score"
                            value="{{ $game ? $game->away_score : '' }}">
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-inline mb-2" style="justify-content:center">
            <div class="form-check mr-2">
                <label class="form-check-label">
                    <input type="checkbox" id="isOvertime" class="form-check-input" name="isOvertime"
                           @if($game && $game->isOvertime) checked @endif>
                    <label for="isOvertime">Овертайм</label>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" id="isTechnicalDefeat" class="form-check-input" name="isTechnicalDefeat"
                           @if($game && $game->isTechnicalDefeat) checked @endif>
                    <label for="isTechnicalDefeat">Техническое поражение</label>
                </label>
            </div>
        </div>
        <div class="form-inline" style="justify-content:center">
            <label class="control-label mr-2" for="playedAt">Дата игры</label>
            <input type="text" id="playedAt" class="form-control" name="playedAt"
                   value="{{ $game ? $game->playedAt : '' }}"
                   readonly>
        </div>

        <div class="row mt-3">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col">
                <input type="number" id="home_shot" class="form-control text-right" name="home_shot"
                       value="{{ $game ? $game->home_shot : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-3 text-center"><strong>Всего бросков</strong></div>
            <div class="col">
                <input type="number" id="away_shot" class="form-control text-right" name="away_shot"
                       value="{{ $game ? $game->away_shot : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>
        <div class="row mt-1">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col">
                <input type="number" id="home_hit" class="form-control text-right" name="home_hit"
                       value="{{ $game ? $game->home_hit : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-3 text-center"><strong>Силовые</strong></div>
            <div class="col">
                <input type="number" id="away_hit" class="form-control text-right" name="away_hit"
                       value="{{ $game ? $game->away_hit : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>
        <div class="row mt-1">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col">
                <input type="text" id="home_attack_time" class="form-control text-right" name="home_attack_time"
                       value="{{ $game && !is_null($game->home_attack_time) ? TextUtils::protocolTime($game->home_attack_time) : '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-3 text-center"><strong>Время в атаке</strong></div>
            <div class="col">
                <input type="text" id="away_attack_time" class="form-control text-right" name="away_attack_time"
                       value="{{ $game && !is_null($game->away_attack_time) ? TextUtils::protocolTime($game->away_attack_time) : '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>
        <div class="row mt-1">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col">
                <input type="text" id="home_pass_percent" class="form-control text-right" name="home_pass_percent"
                       value="{{ $game && !is_null($game->home_pass_percent) ? str_replace('.', ',', $game->home_pass_percent): '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-3 text-center"><strong>Пас</strong></div>
            <div class="col">
                <input type="text" id="away_pass_percent" class="form-control text-right" name="away_pass_percent"
                       value="{{ $game && !is_null($game->away_pass_percent) ? str_replace('.', ',', $game->away_pass_percent): '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>
        <div class="row mt-1">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col">
                <input type="number" id="home_faceoff" class="form-control text-right" name="home_faceoff"
                       value="{{ $game ? $game->home_faceoff : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-3 text-center"><strong>Выигранные вбрасывания</strong></div>
            <div class="col">
                <input type="number" id="away_faceoff" class="form-control text-right" name="away_faceoff"
                       value="{{ $game ? $game->away_faceoff : '' }}" {{ $game && $game->match_id ? 'readonly' : '' }}>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>
        @if (($pair && $pair->tournament->min_players === 6) || ($game && $game->tournament->min_players === 6))
            <div class="row mt-1">
                <div class="col-1 col-md-2 col-xl-3"></div>
                <div class="col">
                    <input id="home_penalty_time" class="form-control text-right"
                           name="home_penalty_time"
                           value="{{ $game && !is_null($game->home_penalty_time) ? TextUtils::protocolTime($game->home_penalty_time) : '' }}"
                            {{ $game && $game->match_id ? 'readonly' : '' }}>
                </div>
                <div class="col-3 text-center"><strong>Штрафные минуты</strong></div>
                <div class="col">
                    <input id="away_penalty_time" class="form-control text-right"
                           name="away_penalty_time"
                           value="{{ $game && !is_null($game->away_penalty_time) ? TextUtils::protocolTime($game->away_penalty_time) : '' }}"
                            {{ $game && $game->match_id ? 'readonly' : '' }}>
                </div>
                <div class="col-1 col-md-2 col-xl-3"></div>
            </div>
            <div class="row mt-1">
                <div class="col-1 col-md-2 col-xl-3"></div>
                <div class="col">
                    <input type="number" id="home_penalty_success" class="form-control text-right float-left w-50"
                           name="home_penalty_success" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->home_penalty_success : '' }}">
                    <input type="number" id="home_penalty_total" class="form-control text-right w-50"
                           name="home_penalty_total" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->home_penalty_total : '' }}">
                </div>
                <div class="col-3 text-center"><strong>Реализация большинства</strong></div>
                <div class="col">
                    <input type="number" id="away_penalty_success" class="form-control text-right float-left w-50"
                           name="away_penalty_success" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->away_penalty_success : '' }}">
                    <input type="number" id="away_penalty_total" class="form-control text-right w-50"
                           name="away_penalty_total" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->away_penalty_total : '' }}">
                </div>
                <div class="col-1 col-md-2 col-xl-3"></div>
            </div>
            <div class="row mt-1">
                <div class="col-1 col-md-2 col-xl-3"></div>
                <div class="col">
                    <input id="home_powerplay_time" class="form-control text-right"
                           name="home_powerplay_time"
                           value="{{ $game && !is_null($game->home_powerplay_time) ? TextUtils::protocolTime($game->home_powerplay_time) : '' }}">
                </div>
                <div class="col-3 text-center"><strong>Минут в большинстве</strong></div>
                <div class="col">
                    <input id="away_powerplay_time" class="form-control text-right"
                           name="away_powerplay_time"
                           value="{{ $game && !is_null($game->away_powerplay_time) ? TextUtils::protocolTime($game->away_powerplay_time) : '' }}">
                </div>
                <div class="col-1 col-md-2 col-xl-3"></div>
            </div>
            <div class="row mt-1">
                <div class="col-1 col-md-2 col-xl-3"></div>
                <div class="col">
                    <input type="number" id="home_shorthanded_goal" class="form-control text-right"
                           name="home_shorthanded_goal" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->home_shorthanded_goal : '' }}">
                </div>
                <div class="col-3 text-center"><strong>Голы в меньшинстве</strong></div>
                <div class="col">
                    <input type="number" id="away_shorthanded_goal" class="form-control text-right"
                           name="away_shorthanded_goal" {{ $game && $game->match_id ? 'readonly' : '' }}
                           value="{{ $game ? $game->away_shorthanded_goal : '' }}">
                </div>
                <div class="col-1 col-md-2 col-xl-3"></div>
            </div>
        @endif
        <div class="text-center mt-2">
            <input type="submit" class="btn btn-primary" value="Сохранить">
        </div>
    </form>

    <h3 class="mt-3">Статистика игроков</h3>
    <div class="row">
        <div class="col">
            <table class="table table-sm table-striped" id="homePlayers"
                   data-id="{{ $pair ? $pair->team_one_id : $game->home_team_id }}">
                <thead class="thead-dark">
                <tr>
                    <th style="">Игрок</th>
                    <th class="text-center" style="width: 5rem;">ПОЗ</th>
                    <th class="text-center" style="width: 4rem;">Г</th>
                    <th class="text-center" style="width: 4rem;">П</th>
                    <th class="text-center" style="width: 4em;"><i class="fas fa-star"></i></th>
                    <th style="width: 0;"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col">
            <table class="table table-sm table-striped" id="awayPlayers"
                   data-id="{{ $pair ? $pair->team_two_id : $game->away_team_id }}">
                <thead class="thead-dark">
                <tr>
                    <th style="">Игрок</th>
                    <th class="text-center" style="width: 5rem;">ПОЗ</th>
                    <th class="text-center" style="width: 4rem;">Г</th>
                    <th class="text-center" style="width: 4rem;">П</th>
                    <th class="text-center" style="width: 4em;"><i class="fas fa-star"></i></th>
                    <th style="width: 0;"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="getGames">Запросить игры для автозаполнения</button>
        <button class="btn btn-danger {{ !$game || !$game->match_id ? 'd-none' : '' }}" id="resetGame">
            Сбросить для ручного ввода
        </button>
    </div>
    <div id="eaGames"></div>
@endsection

@section('script')
    @parent
    @php
        if ($pair) {
            $lastGamesUrl = action('Ajax\EaController@getLastGames') . '?pairId=' . $pair->id;
            $resetGameUrl = $protocolUrl = '';
            if ($game) {
                $saveGameUrl = action(
                    'Ajax\GroupController@editPlayoffGame',
                     ['groupTournament' => $pair->tournament_id, 'groupTournamentPlayoff' => $pair->id, 'groupGamePlayoff' => $game->id]
                 );
                 $resetGameUrl = action(
                    'Ajax\GroupController@resetPlayoffGame',
                     ['groupTournament' => $pair->tournament_id, 'groupTournamentPlayoff' => $pair->id, 'groupGamePlayoff' => $game->id]
                 );
                 $protocolUrl = action(
                    'Ajax\GroupController@createPlayoffProtocol',
                     ['groupTournament' => $pair->tournament_id, 'groupTournamentPlayoff' => $pair->id, 'groupGamePlayoff' => $game->id]
                 );
                 $shareUrl = action(
                    'Ajax\GroupController@sharePlayoffResult',
                    ['groupTournament' => $pair->tournament_id, 'groupTournamentPlayoff' => $pair->id, 'groupGamePlayoff' => $game->id]
                );
             } else {
                $saveGameUrl = action(
                    'Ajax\GroupController@createPlayoffGame',
                     ['groupTournament' => $pair->tournament_id, 'groupTournamentPlayoff' => $pair->id]
                 );
             }
        } else {
            if ($game) {
                $shareUrl = action(
                    'Ajax\GroupController@shareRegularResult',
                    ['groupTournament' => $game->tournament_id, 'groupGameRegular' => $game->id]
                );
            }
            $lastGamesUrl = action('Ajax\EaController@getLastGames') . '?gameId=' . $game->id;
            $saveGameUrl = action(
                'Ajax\GroupController@editRegularGame',
                ['groupTournament' => $game->tournament_id, 'groupGameRegular' => $game->id]
            );
            $resetGameUrl = action(
                'Ajax\GroupController@resetRegularGame',
                 ['groupTournament' => $game->tournament_id, 'groupGameRegular' => $game->id]
             );
             $protocolUrl = action(
                'Ajax\GroupController@createRegularProtocol',
                 ['groupTournament' => $game->tournament_id, 'groupGameRegular' => $game->id]
             );
        }
    @endphp
    <script src="{!! mix('/js/gameFormModule.js') !!}"></script>
    <script>
        $(document).ready(function () {
            $('#playedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

            TRNMNT_gameFormModule.init(
                {
                    lastGames: '{{ $lastGamesUrl }}',
                    saveGame: '{{ $saveGameUrl }}',
                    @if ($game)
                    resetGame: '{{ $resetGameUrl }}',
                    protocol: '{{ $protocolUrl }}'
                    @endif
                },
                    {!! json_encode($protocols) !!},
                    {!! json_encode($players) !!},
                    {!! json_encode($positions) !!},
                    {{ $game && $game->match_id ? $game->match_id : 'null' }}
            );

            @if($game)
            $('#share').on('click', function () {
                TRNMNT_helpers.disableButtons();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ $shareUrl }}',
                    success: function (response) {
                        TRNMNT_helpers.showNotification(response.message);
                        TRNMNT_helpers.enableButtons();
                    },
                });
            });
            @endif
        });
    </script>
@endsection
