<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StorePersonalTournament;
use App\Http\Requests\StoreRequest;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\PersonalTournamentPlayer;
use App\Models\PersonalTournamentPlayoff;
use App\Models\PersonalTournamentWinner;
use App\Utils\GameScoreImage;
use App\Utils\Vk;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Validator;
use VK\Exceptions\Api\VKApiParamAlbumIdException;
use VK\Exceptions\Api\VKApiParamHashException;
use VK\Exceptions\Api\VKApiParamServerException;
use VK\Exceptions\Api\VKApiWallAddPostException;
use VK\Exceptions\Api\VKApiWallAdsPostLimitReachedException;
use VK\Exceptions\Api\VKApiWallAdsPublishedException;
use VK\Exceptions\Api\VKApiWallLinksForbiddenException;
use VK\Exceptions\Api\VKApiWallTooManyRecipientsException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

/**
 * Class PersonalController
 *
 * @package App\Http\Controllers\Ajax
 */
class PersonalController extends Controller
{
    const GAME_RULES = [
        'home_score'        => 'int',
        'away_score'        => 'int',
        'home_shot'         => 'int',
        'isOvertime'        => 'int|min:0|max:1',
        'isShootout'        => 'int|min:0|max:1',
        'isTechnicalDefeat' => 'int|min:0|max:1',
        'playedAt'          => 'date',
    ];

    /**
     * @param StorePersonalTournament $request
     *
     * @return ResponseFactory|Response
     */
    public function create(StorePersonalTournament $request)
    {
        $validatedData = $request->validated();

        $tournament = new PersonalTournament();
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->league_id = $validatedData['league_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $tournament->vk_group_id = $validatedData['vk_group_id'];
        $tournament->startedAt = isset($validatedData['startedAt'])
            ? $validatedData['startedAt']
            : null;

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StorePersonalTournament $request
     * @param int                     $tournamentId
     *
     * @return ResponseFactory|Response
     */
    public function edit(StorePersonalTournament $request, int $tournamentId)
    {
        $validatedData = $request->validated();

        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::find($tournamentId);
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->league_id = $validatedData['league_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $tournament->vk_group_id = $validatedData['vk_group_id'];
        $tournament->startedAt = isset($validatedData['startedAt'])
            ? $validatedData['startedAt']
            : null;

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(StoreRequest $request, int $tournamentId)
    {
        /** @var PersonalTournament $tournament */
        $tournament = PersonalTournament::find($tournamentId);
        $tournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function setWinner(StoreRequest $request, int $tournamentId)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|min:0',
            'place'     => 'required|int|min:1|max:3',
        ]);
        /** @var PersonalTournamentWinner $winner */
        $winner = PersonalTournamentWinner::where('tournament_id', $tournamentId)
            ->where('place', $validatedData['place'])
            ->first();

        $message = $validatedData['place'] . ' место сохранено';
        if (is_null($winner)) {
            $validatedData['tournament_id'] = $tournamentId;
            $winner = new PersonalTournamentWinner($validatedData);
            $winner->save();
        } elseif ($validatedData['player_id'] === '0') {
            $winner->delete();
            $message = $validatedData['place'] . ' место удалено';
        } else {
            $winner->fill($validatedData);
            $winner->save();
        }

        return $this->renderAjax([], $message);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     *
     * @return ResponseFactory|Response
     */
    public function addPlayer(StoreRequest $request, int $tournamentId)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|exists:player,id',
            'division'  => 'required|int|min:1|max:26',
        ]);

        $tournamentPlayer = PersonalTournamentPlayer::withTrashed()
            ->where('tournament_id', $tournamentId)
            ->where('player_id', $validatedData['player_id'])
            ->first();

        if (is_null($tournamentPlayer)) {
            $tournamentPlayer = new PersonalTournamentPlayer;
            $tournamentPlayer->tournament_id = $tournamentId;
            $tournamentPlayer->player_id = $validatedData['player_id'];
            $tournamentPlayer->division = $validatedData['division'];
            $tournamentPlayer->save();
        } else {
            PersonalTournamentPlayer::withTrashed()
                ->where('tournament_id', $tournamentId)
                ->where('player_id', $validatedData['player_id'])
                ->update([
                    'division'  => $validatedData['division'],
                    'deletedAt' => null,
                ]);
        }

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $playerId
     *
     * @return ResponseFactory|Response
     */
    public function editPlayer(StoreRequest $request, int $tournamentId, int $playerId)
    {
        $validatedData = $request->validate([
            'club_id'  => 'string|exists:club,id',
            'division' => 'required|int|min:1|max:26',
        ]);

        PersonalTournamentPlayer::where('tournament_id', $tournamentId)
            ->where('player_id', $playerId)
            ->update($validatedData);

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $playerId
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayer(StoreRequest $request, int $tournamentId, int $playerId)
    {
        PersonalTournamentPlayer::where('tournament_id', $tournamentId)
            ->where('player_id', $playerId)
            ->delete();
        PersonalGameRegular::whereTournamentId($tournamentId)
            ->where('home_player_id', '=', $playerId)
            ->orWhere('away_player_id', '=', $playerId)
            ->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
     *
     * @return ResponseFactory|Response
     */
    public function editRegularGame(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var PersonalGameRegular $game */
        $game = PersonalGameRegular::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        $validatedData = $request->validate(self::GAME_RULES);
        if (!isset($validatedData['isOvertime'])) {
            $validatedData['isOvertime'] = 0;
        }
        if (!isset($validatedData['isShootout'])) {
            $validatedData['isShootout'] = 0;
        }
        if (!isset($validatedData['isTechnicalDefeat'])) {
            $validatedData['isTechnicalDefeat'] = 0;
        }
        $game->fill($validatedData);
        $game->save();

        return $this->renderAjax($validatedData, 'Протокол игры сохранён');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPair(StoreRequest $request, int $tournamentId)
    {
        /** @var PersonalTournament $game */
        $tournament = PersonalTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $rules = [
            'round'         => 'required|int',
            'pair'          => 'required|int',
            'player_one_id' => 'int|exists:player,id',
            'player_two_id' => 'int|exists:player,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();
        $validatedData['tournament_id'] = $tournamentId;

        if (!isset($validatedData['player_one_id']) && !isset($validatedData['player_two_id'])) {
            abort(400, 'Не передан ни один ID игрока');
        }

        /** @var PersonalTournamentPlayoff $pair */
        $pair = PersonalTournamentPlayoff::where('tournament_id', '=', $tournamentId)
            ->where('round', '=', $validatedData['round'])
            ->where('pair', '=', $validatedData['pair'])
            ->first();

        if (is_null($pair)) {
            $pair = new PersonalTournamentPlayoff;
        }
        $pair->fill($validatedData);
        $pair->save();

        return $this->renderAjax(['id' => $pair->id], 'Пара создана');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function updatePair(StoreRequest $request, int $tournamentId, int $pairId)
    {
        /** @var PersonalTournamentPlayoff $pair */
        $pair = PersonalTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $rules = [
            'player_one_id' => 'int|exists:player,id',
            'player_two_id' => 'int|exists:player,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();

        if (!isset($validatedData['player_one_id']) && !isset($validatedData['player_two_id'])) {
            abort(400, 'Не передан ни один ID игрока');
        }

        $pair->fill($validatedData);
        $pair->save();

        return $this->renderAjax([], 'Пара обновлена');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     *
     * @return ResponseFactory|Response
     */
    public function createPlayoffGame(StoreRequest $request, int $tournamentId, int $pairId)
    {
        /** @var PersonalTournamentPlayoff $pair */
        $pair = PersonalTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $validatedData = $request->validate(self::GAME_RULES);
        $game = new PersonalGamePlayoff();
        $game->fill($validatedData);
        $game->playoff_pair_id = $pairId;
        $game->home_player_id = $pair->player_one_id;
        $game->away_player_id = $pair->player_two_id;

        $game->save();

        return $this->renderAjax(['id' => $game->id], 'Игра добавлена');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     *
     * @return ResponseFactory|Response
     */
    public function editPlayoffGame(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var PersonalGamePlayoff $game */
        $game = PersonalGamePlayoff::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $validatedData = $request->validate(self::GAME_RULES);
        if (!isset($validatedData['isOvertime'])) {
            $validatedData['isOvertime'] = 0;
        }
        if (!isset($validatedData['isShootout'])) {
            $validatedData['isShootout'] = 0;
        }
        if (!isset($validatedData['isTechnicalDefeat'])) {
            $validatedData['isTechnicalDefeat'] = 0;
        }
        $game->fill($validatedData);
        $game->save();

        return $this->renderAjax([], 'Протокол игры сохранён');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
     *
     * @return ResponseFactory|Response
     * @throws VKApiException
     * @throws VKApiParamAlbumIdException
     * @throws VKApiParamHashException
     * @throws VKApiParamServerException
     * @throws VKApiWallAddPostException
     * @throws VKApiWallAdsPostLimitReachedException
     * @throws VKApiWallAdsPublishedException
     * @throws VKApiWallLinksForbiddenException
     * @throws VKApiWallTooManyRecipientsException
     * @throws VKClientException
     */
    public function shareRegularResult(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var PersonalGameRegular $game */
        $game = PersonalGameRegular::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        self::postToVk($game);

        return $this->renderAjax([], 'Результат игры опубликован');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     *
     * @return ResponseFactory|Response
     * @throws VKApiException
     * @throws VKApiParamAlbumIdException
     * @throws VKApiParamHashException
     * @throws VKApiParamServerException
     * @throws VKApiWallAddPostException
     * @throws VKApiWallAdsPostLimitReachedException
     * @throws VKApiWallAdsPublishedException
     * @throws VKApiWallLinksForbiddenException
     * @throws VKApiWallTooManyRecipientsException
     * @throws VKClientException
     */
    public function sharePlayoffResult(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var PersonalGamePlayoff $game */
        $game = PersonalGamePlayoff::with(['homePlayer', 'awayPlayer'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        self::postToVk($game);

        return $this->renderAjax([], 'Результат игры опубликован');
    }
}
