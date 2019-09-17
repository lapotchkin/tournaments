@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if ($pair)
        {{ Breadcrumbs::render('personal.tournament.playoff.game.add', $pair) }}
    @else
        {{ Breadcrumbs::render('personal.tournament.regular.game', $game) }}
    @endif

    <h3 class="text-center">
        @if ($pair)
            {{ TextUtils::playoffRound($pair->tournament, $pair->round) }}
        @else
            Тур {{ $game->round }}
        @endif
    </h3>
    <form id="game-form">
        <table class="mb-2 w-100">
            <tbody>
            <tr>
                <td class="text-right pr-3" style="width:40%;">
                    <h2>{{ $pair ? $pair->playerOne->name : $game->homePlayer->name }}</h2>
                    <h4>
                        {{ $pair ? $pair->playerOne->tag : $game->homePlayer->tag }}
                        <span class="badge badge-success text-uppercase">
                            {{ $pair ? $game->playerOne->getClubId($game->tournament_id) : $game->homePlayer->getClubId($game->tournament_id) }}
                        </span>
                    </h4>
                </td>
                <td class="text-right" style="width:4rem;">
                    <input type="text" id="home_score" class="form-control form-control-lg text-center"
                           name="home_score" value="{{ $game ? $game->home_score : '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
                </td>
                <td class="text-center" style="width:1rem;"><h2>:</h2></td>
                <td class="text-left" style="width:4rem;">
                    <input type="text" id="away_score" class="form-control form-control-lg text-center"
                           name="away_score" value="{{ $game ? $game->away_score : '' }}"
                        {{ $game && $game->match_id ? 'readonly' : '' }}>
                </td>
                <td class="text-left pl-3" style="width:40%;">
                    <h2>{{ $pair ? $pair->playerTwo->name : $game->awayPlayer->name }}</h2>
                    <h4>
                        <span class="badge badge-success text-uppercase">
                            {{ $pair ? $game->playerTwo->getClubId($game->tournament_id) : $game->awayPlayer->getClubId($game->tournament_id) }}
                        </span>
                        {{ $pair ? $pair->playerTwo->tag : $game->awayPlayer->tag }}
                    </h4>
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
            <div class="form-check mr-2">
                <label class="form-check-label">
                    <input type="checkbox" id="isShootout" class="form-check-input" name="isShootout"
                           @if($game && $game->isShootout) checked @endif>
                    <label for="isShootout">Буллиты</label>
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
        <div class="text-center mt-2">
            <input type="submit" class="btn btn-primary" value="Сохранить">
        </div>
    </form>
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
                     ['tournamentId' => $pair->tournament_id, 'pairId' => $pair->id, 'gameId' => $game->id]
                 );
                 $resetGameUrl = action(
                    'Ajax\GroupController@resetPlayoffGame',
                     ['tournamentId' => $pair->tournament_id, 'pairId' => $pair->id, 'gameId' => $game->id]
                 );
                 $protocolUrl = action(
                    'Ajax\GroupController@createPlayoffProtocol',
                     ['tournamentId' => $pair->tournament_id, 'pairId' => $pair->id, 'gameId' => $game->id]
                 );
             } else {
                $saveGameUrl = action(
                    'Ajax\GroupController@createPlayoffGame',
                     ['tournamentId' => $pair->tournament_id, 'pairId' => $pair->id]
                 );
             }
        } else {
            $lastGamesUrl = action('Ajax\EaController@getLastGames') . '?gameId=' . $game->id;
            $saveGameUrl = action(
                'Ajax\GroupController@editRegularGame',
                ['tournamentId' => $game->tournament_id, 'gameId' => $game->id]
            );
            $resetGameUrl = action(
                'Ajax\GroupController@resetRegularGame',
                 ['tournamentId' => $game->tournament_id, 'gameId' => $game->id]
             );
             $protocolUrl = action(
                'Ajax\GroupController@createRegularProtocol',
                 ['tournamentId' => $game->tournament_id, 'gameId' => $game->id]
             );
        }
    @endphp
    <script src="{!! mix('/js/gameFormModule.js') !!}"></script>
    <script>
        $(document).ready(function () {
            $('#playedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

        });
    </script>
@endsection
