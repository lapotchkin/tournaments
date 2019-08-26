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
                    <input type="checkbox" id="isovertime" class="form-check-input" name="isOvertime"
                           @if($game->isOvertime) checked @endif>
                    <label for="isovertime">Овертайм</label>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" id="istechnicaldefeat" class="form-check-input" name="isTechnicalDefeat"
                           @if($game->isTechnicalDefeat) checked @endif>
                    <label for="istechnicaldefeat">Техническое поражение</label>
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
    <div class="mt-3">
        <button class="btn btn-primary" id="getGames">Запросить игры для автозаполнения</button>
    </div>
    <div id="eaGames"></div>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#playedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

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

            const $eaGames = $('#eaGames');

            $('#getGames').on('click', function () {
                TRNMNT_helpers.disableButtons();
                $eaGames.empty();
                $.ajax({
                    url: '{{ action('Ajax\EaController@getLastGames', ['gameId' => $game->id]) }}',
                    success: function (response) {
                        TRNMNT_helpers.enableButtons();

                        const $table = $('<table class="table table-sm mt-3"/>');
                        $eaGames.append($table);

                        const $tbody = $('<tbody/>');
                        $table.append($tbody);

                        for (let gameId in response.data) {
                            const game = response.data[gameId].game;
                            const date = new Date(game.playedAt);
                            $tbody.append(`
                                <tr>
                                    <td>${date.getShortDate()}</td>
                                    <td class="text-right">${game.home_team}</td>
                                    <td class="text-right">
                                        <span class="badge badge-primary badge-pill">${game.home_score}</span>
                                    </td>
                                    <td class="text-center">:</td>
                                    <td>
                                        <span class="badge badge-primary badge-pill">${game.away_score}</span>
                                    </td>
                                    <td>${game.away_team}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-primary btn-sm">Заполнить</button>
                                    </td>
                                </tr>
                            `);
                            $tbody.find('button').click(() => fillGameProtocol(game));
                        }
                    },
                    error: TRNMNT_helpers.onErrorAjax,
                    context: TRNMNT_helpers
                });
            });

            function fillGameProtocol(game) {
                for (let field in game) {
                    $(`#${field}`).val(game[field]);
                }
            }
        });
    </script>
@endsection
