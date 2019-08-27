@extends('layouts.site')

@section('title', $game->homeTeam->team->name . ' vs. ' . $game->awayTeam->team->name . ' (Тур ' . $game->round . ') — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.game', $game) }}

    <h3 class="text-center">Тур {{ $game->round }}</h3>
    <form id="game-form">
        <table class="mb-2 w-100">
            <tbody>
            <tr>
                <td class="text-right pr-3" style="width:40%;"><h2>{{ $game->homeTeam->team->name }}</h2></td>
                <td class="text-right" style="width:4rem;">
                    <input type="text" id="home_score" class="form-control form-control-lg text-center"
                           name="home_score" value="{{ $game->home_score }}">
                </td>
                <td class="text-center" style="width:1rem;"><h2>:</h2></td>
                <td class="text-left" style="width:4rem;">
                    <input type="text" id="away_score" class="form-control form-control-lg text-center"
                           name="away_score" value="{{ $game->away_score }}">
                </td>
                <td class="text-left pl-3" style="width:40%;"><h2>{{ $game->awayTeam->team->name }}</h2></td>
            </tr>
            </tbody>
        </table>
        <div class="form-inline mb-2" style="justify-content:center">
            <div class="form-check mr-2">
                <label class="form-check-label">
                    <input type="checkbox" id="isOvertime" class="form-check-input" name="isOvertime"
                           @if($game->isOvertime) checked @endif>
                    <label for="isOvertime">Овертайм</label>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" id="isTechnicalDefeat" class="form-check-input" name="isTechnicalDefeat"
                           @if($game->isTechnicalDefeat) checked @endif>
                    <label for="isTechnicalDefeat">Техническое поражение</label>
                </label>
            </div>
        </div>
        <div class="form-inline" style="justify-content:center">
            <label class="control-label mr-2" for="playedAt">Дата игры</label>
            <input type="text" id="playedAt" class="form-control" name="playedAt" value="{{ $game->playedAt }}"
                   readonly>
        </div>
        <table class="mt-3" style="width: 100%">
            <tbody>
            <tr>
                <td class="w-25"></td>
                <td colspan="2">
                    <input type="number" id="home_shot" class="form-control text-right" name="home_shot"
                           value="{{ $game->home_shot }}">
                </td>
                <th class="text-center w-25">Всего бросков</th>
                <td colspan="2">
                    <input type="number" id="away_shot" class="form-control text-right" name="away_shot"
                           value="{{ $game->away_shot }}">
                </td>
                <td class="w-25"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="number" id="home_hit" class="form-control text-right" name="home_hit"
                           value="{{ $game->home_hit }}">
                </td>
                <th class="text-center">Удары</th>
                <td colspan="2">
                    <input type="number" id="away_hit" class="form-control text-right" name="away_hit"
                           value="{{ $game->away_hit }}">
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="text" id="home_attack_time" class="form-control text-right" name="home_attack_time"
                           value="{{ !is_null($game->home_attack_time) ? TextUtils::protocolTime($game->home_attack_time) : '' }}">
                </td>
                <th class="text-center">Время в атаке</th>
                <td colspan="2">
                    <input type="text" id="away_attack_time" class="form-control text-right" name="away_attack_time"
                           value="{{ !is_null($game->away_attack_time) ? TextUtils::protocolTime($game->away_attack_time) : '' }}">
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="text" id="home_pass_percent" class="form-control text-right" name="home_pass_percent"
                           value="{{ !is_null($game->home_pass_percent) ? str_replace('.', ',', $game->home_pass_percent): '' }}">
                </td>
                <th class="text-center">Пас</th>
                <td colspan="2">
                    <input type="text" id="away_pass_percent" class="form-control text-right" name="away_pass_percent"
                           value="{{ !is_null($game->away_pass_percent) ? str_replace('.', ',', $game->away_pass_percent): '' }}">
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="number" id="home_faceoff" class="form-control text-right" name="home_faceoff"
                           value="{{ $game->home_faceoff }}">
                </td>
                <th class="text-center">Выигранные вбрасывания</th>
                <td colspan="2">
                    <input type="number" id="away_faceoff" class="form-control text-right" name="away_faceoff"
                           value="{{ $game->away_faceoff }}">
                </td>
                <td></td>
            </tr>
            @if ($game->tournament->min_players === 6)
                <tr>
                    <td></td>
                    <td colspan="2">
                        <input type="time" id="home_penalty_time" class="form-control text-right"
                               name="home_penalty_time"
                               value="{{ !is_null($game->home_penalty_time) ?TextUtils::protocolTime($game->home_penalty_time) : '' }}">
                    </td>
                    <th class="text-center">Штрафные минуты</th>
                    <td colspan="2">
                        <input type="time" id="away_penalty_time" class="form-control text-right"
                               name="away_penalty_time"
                               value="{{ !is_null($game->away_penalty_time) ? TextUtils::protocolTime($game->away_penalty_time) : '' }}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="number" id="home_penalty_success" class="form-control text-right"
                               name="home_penalty_success"
                               value="{{ $game->home_penalty_success }}">
                    </td>
                    <td>
                        <input type="number" id="home_penalty_total" class="form-control text-right"
                               name="home_penalty_total"
                               value="{{ $game->home_penalty_total }}">
                    </td>
                    <th class="text-center">Реализация большинства</th>
                    <td>
                        <input type="number" id="away_penalty_success" class="form-control text-right"
                               name="away_penalty_success"
                               value="{{ $game->away_penalty_success }}">
                    </td>
                    <td>
                        <input type="number" id="away_penalty_total" class="form-control text-right"
                               name="away_penalty_total"
                               value="{{ $game->away_penalty_total }}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <input type="time" id="home_powerplay_time" class="form-control text-right"
                               name="home_powerplay_time"
                               value="{{ !is_null($game->home_powerplay_time) ? TextUtils::protocolTime($game->home_powerplay_time) : '' }}">
                    </td>
                    <th class="text-center">Минут в большинстве</th>
                    <td colspan="2">
                        <input type="time" id="away_powerplay_time" class="form-control text-right"
                               name="away_powerplay_time"
                               value="{{ !is_null($game->away_powerplay_time) ? TextUtils::protocolTime($game->away_powerplay_time) : '' }}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <input type="time" id="home_shorthanded_goal" class="form-control text-right"
                               name="home_shorthanded_goal"
                               value="{{ $game->home_shorthanded_goal }}">
                    </td>
                    <th class="text-center">Голы в меньшинстве</th>
                    <td colspan="2">
                        <input type="time" id="away_shorthanded_goal" class="form-control text-right"
                               name="away_shorthanded_goal"
                               value="{{ $game->away_shorthanded_goal }}">
                    </td>
                    <td></td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="text-center mt-2">
            <input type="submit" class="btn btn-primary" value="Сохранить">
        </div>
    </form>

    <h3 class="mt-3">Статистика игроков</h3>
    <div class="row">
        <div class="col">
            <table class="table table-sm table-striped" id="homePlayers">
                <thead>
                <tr>
                    <th style="">Игрок</th>
                    <th class="text-center" style="width: 5rem;">Позиция</th>
                    <th class="text-center" style="width: 5rem;">Голы</th>
                    <th class="text-center" style="width: 5rem;">Пасы</th>
                    <th style="width: 3rem;"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col">
            <table class="table table-sm table-striped" id="awayPlayers">
                <thead>
                <tr>
                    <th style="">Игрок</th>
                    <th class="text-center" style="width: 5rem;">Позиция</th>
                    <th class="text-center" style="width: 5rem;">Голы</th>
                    <th class="text-center" style="width: 5rem;">Пасы</th>
                    <th style="width: 3rem;"></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="getGames">Запросить игры для автозаполнения</button>
        <button class="btn btn-danger d-none" id="resetGame">Сбросить для ручного ввода</button>
    </div>
    <div id="eaGames"></div>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#playedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

            const TRNMNT_gameFormModule = (function () {
                let _isInitialized = false;
                let _$eaGames = null;
                let _$getGames = null;
                let _$resetGame = null;
                let _$homePlayers = null;
                let _$awayPlayers = null;
                let _gameToSave = null;
                let _url = null;
                const _templates = {
                    game: `
                        <tr>
                            <td>#{date}</td>
                            <td class="text-right">#{home_team}</td>
                            <td class="text-right">
                                <span class="badge badge-primary badge-pill">#{home_score}</span>
                            </td>
                            <td class="text-center">:</td>
                            <td>
                                <span class="badge badge-primary badge-pill">#{away_score}</span>
                            </td>
                            <td>#{away_team}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm">Заполнить</button>
                            </td>
                        </tr>`,
                    player: `
                        <tr>
                            <td>#{tag}</td>
                            <td class="text-center">#{position}</td>
                            <td class="text-right">#{goals}</td>
                            <td class="text-right">#{assists}</td>
                            <td></td>
                        </tr>
                    `,
                };

                return {
                    init: _init
                };

                function _init(url) {
                    if (_isInitialized) return;
                    _isInitialized = true;
                    _url = url;
                    _$eaGames = $('#eaGames');
                    _$getGames = $('#getGames');
                    _$resetGame = $('#resetGame');
                    _$homePlayers = $('#homePlayers');
                    _$awayPlayers = $('#awayPlayers');

                    _$getGames.on('click', _onClickGetGames);
                    _$resetGame.on('click', _onClickResetGames);
                }

                function _onClickGetGames() {
                    TRNMNT_helpers.disableButtons();
                    _$eaGames.empty();
                    $.ajax({
                        url: _url.lastGames,
                        success: _onSuccessGetGames,
                        error: TRNMNT_helpers.onErrorAjax,
                        context: TRNMNT_helpers
                    });
                }

                function _onClickResetGames() {
                    for (let field in _gameToSave.game) {
                        const $field = $(`#${field}`);
                        if (['checkbox', 'radio'].indexOf($field.attr('type')) !== -1) {
                            $field.prop('checked', false);
                        } else {
                            $field.val('');
                            if (field !== 'playedAt') $field.prop('readonly', false);
                        }
                    }
                    _gameToSave = null;
                    _$resetGame.addClass('d-none');
                }

                function _onSuccessGetGames(response) {
                    TRNMNT_helpers.enableButtons();

                    const $table = $('<table class="table table-sm table-striped mt-3"/>');
                    _$eaGames.append($table);

                    const $tbody = $('<tbody/>');
                    $table.append($tbody);

                    for (let gameId in response.data) {
                        const game = response.data[gameId].game;
                        const date = new Date(game.playedAt);
                        const $row = $(_templates.game.format({
                            date: date.getShortDate(),
                            home_team: game.home_team,
                            away_team: game.away_team,
                            home_score: game.home_score,
                            away_score: game.away_score,
                        }));
                        $row.find('button').click(() => _fillGameProtocol(response.data[gameId]));
                        $tbody.append($row);
                    }
                }

                function _fillGameProtocol(game) {
                    _$resetGame.removeClass('d-none');
                    _gameToSave = game;
                    for (let field in _gameToSave.game) {
                        const $field = $(`#${field}`);
                        $field.val(_gameToSave.game[field]);
                        if (_gameToSave.game[field] !== '') $field.prop('readonly', true);
                    }
                    _fillPlayers(_gameToSave.players);
                }

                function _fillPlayers(players) {
                    for (let side in players) {
                        const $tbody = side === 'home' ? _$homePlayers.find('tbody') : _$awayPlayers.find('tbody');
                        for (let player of players[side]) {
                            $tbody.append(_templates.player.format({
                                tag: player.name,
                                position: player.position_id,
                                goals: player.goals,
                                assists: player.assists,
                            }));
                        }
                    }
                }
            }());

            TRNMNT_gameFormModule.init({
                lastGames: '{{ action('Ajax\EaController@getLastGames', ['gameId' => $game->id]) }}'
            });

            TRNMNT_sendData({
                selector: '#game-form',
                method: 'post',
                url: '{{ action('Ajax\GroupController@editRegularGame', ['tournamentId' => $game->tournament_id, 'gameId' => $game->id])}}',
                success: function (response) {
                    console.log(response);
                },
                error: TRNMNT_helpers.onErrorAjax,
                context: TRNMNT_helpers
            });
        });
    </script>
@endsection
