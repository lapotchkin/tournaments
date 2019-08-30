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
                           name="home_score" value="{{ $game->home_score }}"
                        {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <td class="text-center" style="width:1rem;"><h2>:</h2></td>
                <td class="text-left" style="width:4rem;">
                    <input type="text" id="away_score" class="form-control form-control-lg text-center"
                           name="away_score" value="{{ $game->away_score }}"
                        {{ $game->match_id ? 'readonly' : '' }}>
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
                           value="{{ $game->home_shot }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <th class="text-center w-25">Всего бросков</th>
                <td colspan="2">
                    <input type="number" id="away_shot" class="form-control text-right" name="away_shot"
                           value="{{ $game->away_shot }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <td class="w-25"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="number" id="home_hit" class="form-control text-right" name="home_hit"
                           value="{{ $game->home_hit }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <th class="text-center">Удары</th>
                <td colspan="2">
                    <input type="number" id="away_hit" class="form-control text-right" name="away_hit"
                           value="{{ $game->away_hit }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <input type="text" id="home_attack_time" class="form-control text-right" name="home_attack_time"
                           value="{{ !is_null($game->home_attack_time) ? TextUtils::protocolTime($game->home_attack_time) : '' }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <th class="text-center">Время в атаке</th>
                <td colspan="2">
                    <input type="text" id="away_attack_time" class="form-control text-right" name="away_attack_time"
                           value="{{ !is_null($game->away_attack_time) ? TextUtils::protocolTime($game->away_attack_time) : '' }}" {{ $game->match_id ? 'readonly' : '' }}>
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
                           value="{{ $game->home_faceoff }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <th class="text-center">Выигранные вбрасывания</th>
                <td colspan="2">
                    <input type="number" id="away_faceoff" class="form-control text-right" name="away_faceoff"
                           value="{{ $game->away_faceoff }}" {{ $game->match_id ? 'readonly' : '' }}>
                </td>
                <td></td>
            </tr>
            @if ($game->tournament->min_players === 6)
                <tr>
                    <td></td>
                    <td colspan="2">
                        <input type="time" id="home_penalty_time" class="form-control text-right"
                               name="home_penalty_time"
                               value="{{ !is_null($game->home_penalty_time) ?TextUtils::protocolTime($game->home_penalty_time) : '' }}" {{ $game->match_id ? 'readonly' : '' }}>
                    </td>
                    <th class="text-center">Штрафные минуты</th>
                    <td colspan="2">
                        <input type="time" id="away_penalty_time" class="form-control text-right"
                               name="away_penalty_time"
                               value="{{ !is_null($game->away_penalty_time) ? TextUtils::protocolTime($game->away_penalty_time) : '' }}" {{ $game->match_id ? 'readonly' : '' }}>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="number" id="home_penalty_success" class="form-control text-right"
                               name="home_penalty_success" {{ $game->match_id ? 'readonly' : '' }}
                               value="{{ $game->home_penalty_success }}">
                    </td>
                    <td>
                        <input type="number" id="home_penalty_total" class="form-control text-right"
                               name="home_penalty_total" {{ $game->match_id ? 'readonly' : '' }}
                               value="{{ $game->home_penalty_total }}">
                    </td>
                    <th class="text-center">Реализация большинства</th>
                    <td>
                        <input type="number" id="away_penalty_success" class="form-control text-right"
                               name="away_penalty_success" {{ $game->match_id ? 'readonly' : '' }}
                               value="{{ $game->away_penalty_success }}">
                    </td>
                    <td>
                        <input type="number" id="away_penalty_total" class="form-control text-right"
                               name="away_penalty_total" {{ $game->match_id ? 'readonly' : '' }}
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
                               name="home_shorthanded_goal" {{ $game->match_id ? 'readonly' : '' }}
                               value="{{ $game->home_shorthanded_goal }}">
                    </td>
                    <th class="text-center">Голы в меньшинстве</th>
                    <td colspan="2">
                        <input type="time" id="away_shorthanded_goal" class="form-control text-right"
                               name="away_shorthanded_goal" {{ $game->match_id ? 'readonly' : '' }}
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
            <table class="table table-sm table-striped" id="homePlayers" data-id="{{ $game->home_team_id }}">
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
            <table class="table table-sm table-striped" id="awayPlayers" data-id="{{ $game->away_team_id }}">
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
        <button class="btn btn-danger {{ !$game->match_id ? 'd-none' : '' }}" id="resetGame">
            Сбросить для ручного ввода
        </button>
    </div>
    <div id="eaGames"></div>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('#playedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

            TRNMNT_gameFormModule.init(
                {
                    lastGames: '{{ action('Ajax\EaController@getLastGames', ['gameId' => $game->id]) }}',
                    saveGame: '{{ action('Ajax\GroupController@editRegularGame', ['tournamentId' => $game->tournament_id, 'gameId' => $game->id])}}',
                    resetGame: '{{ action('Ajax\GroupController@resetRegularGame', ['tournamentId' => $game->tournament_id, 'gameId' => $game->id])}}',
                    protocol: '{{ action('Ajax\GroupController@createRegularProtocol', ['tournamentId' => $game->tournament_id, 'gameId' => $game->id])}}'
                },
                {!! json_encode($protocols) !!},
                {!! json_encode($players) !!},
                {!! json_encode($positions) !!},
                {{ $game->match_id ? $game->match_id : 'null' }}
            );
        });
    </script>
@endsection
