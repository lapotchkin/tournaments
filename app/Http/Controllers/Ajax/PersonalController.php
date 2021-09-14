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
use App\Models\Player;
use App\Utils\TournamentScheduler;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    protected const GAME_RULES = [
        'home_score'        => 'int',
        'away_score'        => 'int',
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
        $tournament->startedAt = $validatedData['startedAt'] ?? null;
        $tournament->playoff_limit = $validatedData['playoff_limit'] ?? null;

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StorePersonalTournament $request
     * @param PersonalTournament      $personalTournament
     *
     * @return ResponseFactory|Response
     */
    public function edit(StorePersonalTournament $request, PersonalTournament $personalTournament)
    {
        $validatedData = $request->validated();

        $personalTournament->platform_id = $validatedData['platform_id'];
        $personalTournament->app_id = $validatedData['app_id'];
        $personalTournament->league_id = $validatedData['league_id'];
        $personalTournament->title = $validatedData['title'];
        $personalTournament->playoff_rounds = $validatedData['playoff_rounds'];
        $personalTournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $personalTournament->vk_group_id = $validatedData['vk_group_id'];
        $personalTournament->startedAt = $validatedData['startedAt'] ?? null;
        $personalTournament->playoff_limit = $validatedData['playoff_limit'] ?? null;

        $personalTournament->save();

        return $this->renderAjax(['id' => $personalTournament->id]);
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(Request $request, PersonalTournament $personalTournament)
    {
        $personalTournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     */
    public function addSchedule(Request $request, PersonalTournament $personalTournament)
    {
        if (count($personalTournament->regularGames)) {
            abort(400, 'Расписание уже создано');
        }

        $validatedData = $request->validate([
            'gamesCount' => 'required|int|min:1|max:2',
            'rounds'     => 'required|int|min:1|max:10',
        ]);

        $divisions = [];
        foreach ($personalTournament->tournamentPlayers as $tournamentPlayer) {
            $divisions[$tournamentPlayer->division][] = $tournamentPlayer->player_id;
        }

        foreach ($divisions as $division) {
            $divisionSchedule = TournamentScheduler::generate($division);

            for ($repeat = 1; $repeat < $validatedData['rounds'] + 1; $repeat += 1) {
                foreach ($divisionSchedule as $round => $games) {
                    foreach ($games as $teams) {
                        $gameOne = new PersonalGameRegular([
                            'tournament_id'  => $personalTournament->id,
                            'round'          => ($round + 1) * $repeat,
                            'home_player_id' => $teams[0],
                            'away_player_id' => $teams[1],
                        ]);
                        $gameOne->save();

                        if ($validatedData['gamesCount'] === 2) {
                            $gameTwo = new PersonalGameRegular([
                                'tournament_id'  => $personalTournament->id,
                                'round'          => ($round + 1) * $repeat,
                                'home_player_id' => $teams[1],
                                'away_player_id' => $teams[0],
                            ]);
                            $gameTwo->save();
                        }
                    }
                }
            }
        }

        return $this->renderAjax([], 'Расписание создано');
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteSchedule(Request $request, PersonalTournament $personalTournament)
    {
        foreach ($personalTournament->regularGames as $regularGame) {
            $regularGame->delete();
        }

        return $this->renderAjax([], 'Расписание удалено');
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function setWinner(Request $request, PersonalTournament $personalTournament)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|min:0',
            'place'     => 'required|int|min:1|max:3',
        ]);
        $winner = PersonalTournamentWinner::where('tournament_id', $personalTournament->id)
            ->where('place', $validatedData['place'])
            ->first();

        $message = $validatedData['place'] . ' место сохранено';
        if (is_null($winner)) {
            $validatedData['tournament_id'] = $personalTournament->id;
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
     * @param StoreRequest       $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     */
    public function addPlayer(Request $request, PersonalTournament $personalTournament)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|exists:player,id',
            'division'  => 'required|int|min:1|max:26',
        ]);

        $tournamentPlayer = PersonalTournamentPlayer::withTrashed()
            ->where('tournament_id', $personalTournament->id)
            ->where('player_id', $validatedData['player_id'])
            ->first();

        if (is_null($tournamentPlayer)) {
            $tournamentPlayer = new PersonalTournamentPlayer();
            $tournamentPlayer->tournament_id = $personalTournament->id;
            $tournamentPlayer->player_id = $validatedData['player_id'];
            $tournamentPlayer->division = $validatedData['division'];
            $tournamentPlayer->save();
        } else {
            PersonalTournamentPlayer::withTrashed()
                ->where('tournament_id', $personalTournament->id)
                ->where('player_id', $validatedData['player_id'])
                ->update([
                    'division'  => $validatedData['division'],
                    'deletedAt' => null,
                ]);
        }

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest       $request
     * @param PersonalTournament $personalTournament
     * @param Player             $player
     *
     * @return ResponseFactory|Response
     */
    public function editPlayer(Request $request, PersonalTournament $personalTournament, Player $player)
    {
        $validatedData = $request->validate([
            'club_id'  => 'string|exists:club,id',
            'division' => 'required|int|min:1|max:26',
        ]);

        PersonalTournamentPlayer::where('tournament_id', $personalTournament->id)
            ->where('player_id', $player->id)
            ->update($validatedData);

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest       $request
     * @param PersonalTournament $personalTournament
     * @param Player             $player
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayer(Request $request, PersonalTournament $personalTournament, Player $player)
    {
        PersonalTournamentPlayer::where('tournament_id', $personalTournament->id)
            ->where('player_id', $player->id)
            ->delete();
        PersonalGameRegular::whereTournamentId($personalTournament->id)
            ->where('home_player_id', '=', $player->id)
            ->orWhere('away_player_id', '=', $player->id)
            ->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request             $request
     * @param PersonalTournament  $personalTournament
     * @param PersonalGameRegular $personalGameRegular
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function editRegularGame(
        Request             $request,
        PersonalTournament  $personalTournament,
        PersonalGameRegular $personalGameRegular
    )
    {
        $personalGameRegular->load(['homePlayer', 'awayPlayer']);
        if ($personalGameRegular->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();
        $validatedData['isOvertime'] = $validatedData['isOvertime'] ?? 0;
        $validatedData['isShootout'] = $validatedData['isShootout'] ?? 0;
        $validatedData['isTechnicalDefeat'] = $validatedData['isTechnicalDefeat'] ?? 0;

        $personalGameRegular->fill($validatedData);
        $personalGameRegular->save();

        return $this->renderAjax($validatedData, 'Протокол игры сохранён');
    }

    /**
     * @param Request            $request
     * @param PersonalTournament $personalTournament
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPair(Request $request, PersonalTournament $personalTournament)
    {
        $input = json_decode($request->getContent(), true);
        $rules = [
            'round'         => 'required|int',
            'pair'          => 'required|int',
            'player_one_id' => 'int|exists:player,id',
            'player_two_id' => 'int|exists:player,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();
        $validatedData['tournament_id'] = $personalTournament->id;

        if (!isset($validatedData['player_one_id']) && !isset($validatedData['player_two_id'])) {
            abort(400, 'Не передан ни один ID игрока');
        }

        $personalTournamentPlayoff = PersonalTournamentPlayoff::where('tournament_id', '=', $personalTournament->id)
            ->where('round', '=', $validatedData['round'])
            ->where('pair', '=', $validatedData['pair'])
            ->first();

        if (is_null($personalTournamentPlayoff)) {
            $personalTournamentPlayoff = new PersonalTournamentPlayoff();
        }
        $personalTournamentPlayoff->fill($validatedData);
        $personalTournamentPlayoff->save();

        return $this->renderAjax(['id' => $personalTournamentPlayoff->id], 'Пара создана');
    }

    /**
     * @param StoreRequest              $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function updatePair(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff
    )
    {
        if ($personalTournamentPlayoff->tournament_id !== $personalTournament->id) {
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

        $personalTournamentPlayoff->fill($validatedData);
        $personalTournamentPlayoff->save();

        return $this->renderAjax([], 'Пара обновлена');
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     *
     * @return ResponseFactory|Response
     */
    public function createPlayoffGame(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff
    )
    {
        if ($personalTournamentPlayoff->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        $validatedData = $request->validate(self::GAME_RULES);
        $game = new PersonalGamePlayoff();
        $game->fill($validatedData);
        $game->playoff_pair_id = $personalTournamentPlayoff->id;
        $game->home_player_id = $personalTournamentPlayoff->player_one_id;
        $game->away_player_id = $personalTournamentPlayoff->player_two_id;

        $game->save();

        return $this->renderAjax(['id' => $game->id], 'Игра добавлена');
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     * @param PersonalGamePlayoff       $personalGamePlayoff
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function editPlayoffGame(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff,
        PersonalGamePlayoff       $personalGamePlayoff
    )
    {
        $personalGamePlayoff->load(['homePlayer', 'awayPlayer']);
        if (
            $personalGamePlayoff->playoff_pair_id !== $personalTournamentPlayoff->id
            || $personalGamePlayoff->playoffPair->tournament_id !== $personalTournament->id
        ) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();
        $validatedData['isOvertime'] = $validatedData['isOvertime'] ?? 0;
        $validatedData['isShootout'] = $validatedData['isShootout'] ?? 0;
        $validatedData['isTechnicalDefeat'] = $validatedData['isTechnicalDefeat'] ?? 0;

        $personalGamePlayoff->fill($validatedData);
        $personalGamePlayoff->save();

        return $this->renderAjax([], 'Протокол игры сохранён');
    }

    /**
     * @param Request             $request
     * @param PersonalTournament  $personalTournament
     * @param PersonalGameRegular $personalGameRegular
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
    public function shareRegularResult(
        Request             $request,
        PersonalTournament  $personalTournament,
        PersonalGameRegular $personalGameRegular
    )
    {
        $personalGameRegular->load(['homePlayer', 'awayPlayer']);
        if ($personalGameRegular->tournament_id !== $personalTournament->id) {
            abort(404);
        }

        self::postToVk($personalGameRegular);

        return $this->renderAjax([], 'Результат игры опубликован');
    }

    /**
     * @param Request                   $request
     * @param PersonalTournament        $personalTournament
     * @param PersonalTournamentPlayoff $personalTournamentPlayoff
     * @param PersonalGamePlayoff       $personalGamePlayoff
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
    public function sharePlayoffResult(
        Request                   $request,
        PersonalTournament        $personalTournament,
        PersonalTournamentPlayoff $personalTournamentPlayoff,
        PersonalGamePlayoff       $personalGamePlayoff
    )
    {
        $personalGamePlayoff->load(['homePlayer', 'awayPlayer']);
        if (
            $personalGamePlayoff->playoff_pair_id !== $personalTournamentPlayoff->id
            || $personalGamePlayoff->playoffPair->tournament_id !== $personalTournament->id
        ) {
            abort(404);
        }

        self::postToVk($personalGamePlayoff);

        return $this->renderAjax([], 'Результат игры опубликован');
    }
}
