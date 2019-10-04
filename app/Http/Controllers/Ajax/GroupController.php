<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreGroupTournament;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\GroupGamePlayoff;
use App\Models\GroupGamePlayoffPlayer;
use App\Models\GroupGameRegular;
use App\Models\GroupGameRegularPlayer;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\GroupTournamentTeam;
use App\Models\GroupTournamentWinner;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
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
 * Class GroupController
 * @package App\Http\Controllers\Ajax
 */
class GroupController extends Controller
{
    const GAME_RULES = [
        'game'                          => 'required|array',
        'game.home_score'               => 'required|int',
        'game.away_score'               => 'required|int',
        'game.home_shot'                => 'int',
        'game.away_shot'                => 'int',
        'game.home_hit'                 => 'int',
        'game.away_hit'                 => 'int',
        'game.home_attack_time'         => 'date_format:i:s',
        'game.away_attack_time'         => 'date_format:i:s',
        'game.home_pass_percent'        => 'numeric',
        'game.away_pass_percent'        => 'numeric',
        'game.home_faceoff'             => 'int',
        'game.away_faceoff'             => 'int',
        'game.home_penalty_time'        => 'date_format:i:s',
        'game.away_penalty_time'        => 'date_format:i:s',
        'game.home_penalty_total'       => 'int',
        'game.away_penalty_total'       => 'int',
        'game.home_penalty_success'     => 'int',
        'game.away_penalty_success'     => 'int',
        'game.home_powerplay_time'      => 'date_format:i:s',
        'game.away_powerplay_time'      => 'date_format:i:s',
        'game.home_shorthanded_goal'    => 'int',
        'game.away_shorthanded_goal'    => 'int',
        'game.isOvertime'               => 'int|min:0|max:1',
        'game.isShootout'               => 'int|min:0|max:1',
        'game.isTechnicalDefeat'        => 'int|min:0|max:1',
        'game.playedAt'                 => 'date',
        'game.match_id'                 => 'int',
        'players'                       => 'sometimes|required|array',
        'players.home'                  => 'sometimes|required|array',
        'players.away'                  => 'sometimes|required|array',
        'players.*.team_id'             => 'int',
        'players.*.player_id'           => 'int',
        'players.*.class_id'            => 'int',
        'players.*.position_id'         => 'int',
        'players.*.star'                => 'int',
        'players.*.time_on_ice_seconds' => 'int',
        'players.*.goals'               => 'int',
        'players.*.power_play_goals'    => 'int',
        'players.*.shorthanded_goals'   => 'int',
        'players.*.game_winning_goals'  => 'int',
        'players.*.assists'             => 'int',
        'players.*.shots'               => 'int',
        'players.*.plus_minus'          => 'int',
        'players.*.faceoff_win'         => 'int',
        'players.*.faceoff_lose'        => 'int',
        'players.*.blocks'              => 'int',
        'players.*.giveaways'           => 'int',
        'players.*.takeaways'           => 'int',
        'players.*.hits'                => 'int',
        'players.*.penalty_minutes'     => 'int',
        'players.*.rating_defense'      => 'float',
        'players.*.rating_offense'      => 'float',
        'players.*.rating_teamplay'     => 'float',
        'players.*.shots_on_goal'       => 'int',
        'players.*.saves'               => 'int',
        'players.*.breakeaway_shots'    => 'int',
        'players.*.breakeaway_saves'    => 'int',
        'players.*.penalty_shots'       => 'int',
        'players.*.penalty_saves'       => 'int',
        'players.*.goals_against'       => 'int',
        'players.*.pokechecks'          => 'int',
        'players.*.isWin'               => 'int|min:0|max:1',
        'players.*.isGoalie'            => 'int|min:0|max:1',
    ];

    const GAME_EMPTY = [
        'home_score'            => null,
        'away_score'            => null,
        'home_shot'             => null,
        'away_shot'             => null,
        'home_hit'              => null,
        'away_hit'              => null,
        'home_attack_time'      => null,
        'away_attack_time'      => null,
        'home_pass_percent'     => null,
        'away_pass_percent'     => null,
        'home_faceoff'          => null,
        'away_faceoff'          => null,
        'home_penalty_time'     => null,
        'away_penalty_time'     => null,
        'home_penalty_total'    => null,
        'away_penalty_total'    => null,
        'home_penalty_success'  => null,
        'away_penalty_success'  => null,
        'home_powerplay_time'   => null,
        'away_powerplay_time'   => null,
        'home_shorthanded_goal' => null,
        'away_shorthanded_goal' => null,
        'isOvertime'            => 0,
        'isTechnicalDefeat'     => 0,
        'match_id'              => null,
    ];

    /**
     * @param StoreGroupTournament $request
     * @return ResponseFactory|Response
     */
    public function create(StoreGroupTournament $request)
    {
        $validatedData = $request->validated();

        $tournament = new GroupTournament;
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->min_players = $validatedData['min_players'];
        $tournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $tournament->vk_group_id = $validatedData['vk_group_id'];

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreGroupTournament $request
     * @param int                  $tournamentId
     * @return ResponseFactory|Response
     */
    public function edit(StoreGroupTournament $request, int $tournamentId)
    {
        $validatedData = $request->validated();

        $tournament = GroupTournament::find($tournamentId);
        $tournament->platform_id = $validatedData['platform_id'];
        $tournament->app_id = $validatedData['app_id'];
        $tournament->title = $validatedData['title'];
        $tournament->playoff_rounds = $validatedData['playoff_rounds'];
        $tournament->min_players = $validatedData['min_players'];
        $tournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $tournament->vk_group_id = $validatedData['vk_group_id'];

        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(StoreRequest $request, int $tournamentId)
    {
        $tournament = GroupTournament::find($tournamentId);
        $tournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return ResponseFactory|Response
     */
    public function setWinner(StoreRequest $request, int $tournamentId)
    {
        $validatedData = $request->validate([
            'team_id' => 'required|int|min:0',
            'place'   => 'required|int|min:1|max:3',
        ]);
        $winner = GroupTournamentWinner::where('tournament_id', $tournamentId)
            ->where('place', $validatedData['place'])
            ->first();

        $message = $validatedData['place'] . ' место сохранено';
        if (is_null($winner)) {
            $validatedData['tournament_id'] = $tournamentId;
            $winner = new GroupTournamentWinner($validatedData);
            $winner->save();
        } elseif ($validatedData['team_id'] === '0') {
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
     * @return ResponseFactory|Response
     */
    public function addTeam(StoreRequest $request, int $tournamentId)
    {
        $validatedData = $request->validate([
            'team_id'  => 'required|int|exists:team,id',
            'division' => 'required|int|min:1|max:26',
        ]);

        $tournamentTeam = GroupTournamentTeam::withTrashed()
            ->where('tournament_id', $tournamentId)
            ->where('team_id', $validatedData['team_id'])
            ->first();

        if (is_null($tournamentTeam)) {
            $tournamentTeam = new GroupTournamentTeam;
            $tournamentTeam->tournament_id = $tournamentId;
            $tournamentTeam->team_id = $validatedData['team_id'];
            $tournamentTeam->division = $validatedData['division'];
            $tournamentTeam->save();
        } else {
            GroupTournamentTeam::withTrashed()
                ->where('tournament_id', $tournamentId)
                ->where('team_id', $validatedData['team_id'])
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
     * @param int          $teamId
     * @return ResponseFactory|Response
     */
    public function editTeam(StoreRequest $request, int $tournamentId, int $teamId)
    {
        $validatedData = $request->validate([
            'division' => 'required|int|min:1|max:26',
        ]);

        GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->update(['division' => $validatedData['division']]);

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $teamId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteTeam(StoreRequest $request, int $tournamentId, int $teamId)
    {
        GroupTournamentTeam::where('tournament_id', $tournamentId)
            ->where('team_id', $teamId)
            ->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function editRegularGame(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $attributes = $game->attributesToArray();
        foreach ($validatedData['game'] as $field => $value) {
            if (!array_key_exists($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $game->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $game->{$field} = '00:' . $value;
            } else {
                $game->{$field} = $value;
            }
        }
        $game->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $gameId;
                    $player = GroupGameRegularPlayer::where('game_id', '=', $gameId)
                        ->where('team_id', '=', $playerData['team_id'])
                        ->where('player_id', '=', $playerData['player_id'])
                        ->first();
                    if (!is_null($player)) {
                        $player->fill($playerData);
                    } else {
                        $player = new GroupGameRegularPlayer($playerData);
                    }
                    $player->save();
                }
            }
        }

        return $this->renderAjax([], 'Протокол игры сохранён');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function resetRegularGame(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        $emptyGame = self::GAME_EMPTY;
        $emptyGame['isShootout'] = 0;
        $game->fill($emptyGame);
        $game->save();
        foreach ($game->protocols as $protocol) {
            $protocol->delete();
        }

        return $this->renderAjax([], 'Протокол игры обнулён');
    }

    /**
     * @param StoreRequest $request
     * @return ResponseFactory|Response
     */
    public function createRegularProtocol(StoreRequest $request, int $tournamentId, int $gameId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $protocol = new GroupGameRegularPlayer;
        $protocol->fill($input);
        $protocol->save();

        return $this->renderAjax(['id' => $protocol->id]);
    }

    public function updateRegularProtocol(StoreRequest $request, int $tournamentId, int $gameId, int $protocolId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        $protocol = GroupGameRegularPlayer::find($protocolId);
        if (is_null($game) || $game->tournament_id !== $tournamentId || is_null($protocol)) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $protocol->fill($input);
        $protocol->save();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $protocolId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteRegularProtocol(StoreRequest $request, int $tournamentId, int $gameId, int $protocolId)
    {
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        $protocol = GroupGameRegularPlayer::find($protocolId);
        if (is_null($game) || $game->tournament_id !== $tournamentId || is_null($protocol)) {
            abort(404);
        }

        $protocol->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPair(StoreRequest $request, int $tournamentId)
    {
        /** @var GroupTournament $game */
        $tournament = GroupTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $rules = [
            'round'       => 'required|int',
            'pair'        => 'required|int',
            'team_one_id' => 'int|exists:team,id',
            'team_two_id' => 'int|exists:team,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();
        $validatedData['tournament_id'] = $tournamentId;

        if (!isset($validatedData['team_one_id']) && !isset($validatedData['team_two_id'])) {
            abort(400, 'Не передан ни один ID команды');
        }

        /** @var GroupTournamentPlayoff $pair */
        $pair = GroupTournamentPlayoff::where('tournament_id', '=', $tournamentId)
            ->where('round', '=', $validatedData['round'])
            ->where('pair', '=', $validatedData['pair'])
            ->first();

        if (is_null($pair)) {
            $pair = new GroupTournamentPlayoff;
        }
        $pair->fill($validatedData);
        $pair->save();

        return $this->renderAjax(['id' => $pair->id], 'Пара создана');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @return ResponseFactory|Response
     */
    public function updatePair(StoreRequest $request, int $tournamentId, int $pairId)
    {
        /** @var GroupTournamentPlayoff $pair */
        $pair = GroupTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $rules = [
            'team_one_id' => 'int|exists:team,id',
            'team_two_id' => 'int|exists:team,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();

        if (!isset($validatedData['team_one_id']) && !isset($validatedData['team_two_id'])) {
            abort(400, 'Не передан ни один ID команды');
        }

        $pair->fill($validatedData);
        $pair->save();

        return $this->renderAjax([], 'Пара обновлена');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPlayoffGame(StoreRequest $request, int $tournamentId, int $pairId)
    {
        /** @var GroupTournamentPlayoff $pair */
        $pair = GroupTournamentPlayoff::find($pairId);
        if (is_null($pair) || $pair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $game = new GroupGamePlayoff;
        $attributes = $game->getFillable();
        foreach ($validatedData['game'] as $field => $value) {
            if (!in_array($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $game->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $game->{$field} = '00:' . $value;
            } else {
                $game->{$field} = $value;
            }
        }
        $game->playoff_pair_id = $pairId;
        $game->home_team_id = $pair->team_one_id;
        $game->away_team_id = $pair->team_two_id;

        $game->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $game->id;
                    $player = GroupGamePlayoffPlayer::where('game_id', '=', $game->id)
                        ->where('team_id', '=', $playerData['team_id'])
                        ->where('player_id', '=', $playerData['player_id'])
                        ->first();
                    if (!is_null($player)) {
                        $player->fill($playerData);
                    } else {
                        $player = new GroupGamePlayoffPlayer($playerData);
                    }
                    $player->save();
                }
            }
        }

        return $this->renderAjax(['id' => $game->id], 'Игра добавлена');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function editPlayoffGame(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $attributes = $game->getFillable();
        foreach ($validatedData['game'] as $field => $value) {
            if (!in_array($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $game->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $game->{$field} = '00:' . $value;
            } else {
                $game->{$field} = $value;
            }
        }
        $game->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $gameId;
                    $player = GroupGamePlayoffPlayer::where('game_id', '=', $gameId)
                        ->where('team_id', '=', $playerData['team_id'])
                        ->where('player_id', '=', $playerData['player_id'])
                        ->first();
                    if (!is_null($player)) {
                        $player->fill($playerData);
                    } else {
                        $player = new GroupGamePlayoffPlayer($playerData);
                    }
                    $player->save();
                }
            }
        }

        return $this->renderAjax([], 'Протокол игры сохранён');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function resetPlayoffGame(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $game->fill(self::GAME_EMPTY);
        $game->save();
        foreach ($game->protocols as $protocol) {
            $protocol->delete();
        }

        return $this->renderAjax([], 'Протокол игры обнулён');
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @return ResponseFactory|Response
     */
    public function createPlayoffProtocol(StoreRequest $request, int $tournamentId, int $pairId, int $gameId)
    {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $protocol = new GroupGamePlayoffPlayer;
        $protocol->fill($input);
        $protocol->save();

        return $this->renderAjax(['id' => $protocol->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @param int          $protocolId
     * @return ResponseFactory|Response
     */
    public function updatePlayoffProtocol(
        StoreRequest $request,
        int $tournamentId,
        int $pairId,
        int $gameId,
        int $protocolId
    ) {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        $protocol = GroupGamePlayoffPlayer::find($protocolId);
        if (
            is_null($game)
            || $game->playoff_pair_id !== $pairId
            || $game->playoffPair->tournament_id !== $tournamentId
            || is_null($protocol)
        ) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $protocol->fill($input);
        $protocol->save();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $pairId
     * @param int          $gameId
     * @param int          $protocolId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayoffProtocol(
        StoreRequest $request,
        int $tournamentId,
        int $pairId,
        int $gameId,
        int $protocolId
    ) {
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['protocols.player', 'homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        $protocol = GroupGamePlayoffPlayer::find($protocolId);
        if (
            is_null($game)
            || $game->playoff_pair_id !== $pairId
            || $game->playoffPair->tournament_id !== $tournamentId
            || is_null($protocol)
        ) {
            abort(404);
        }

        $protocol->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $tournamentId
     * @param int          $gameId
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
        /** @var GroupGameRegular $game */
        $game = GroupGameRegular::with(['homeTeam.team', 'awayTeam.team'])
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
        /** @var GroupGamePlayoff $game */
        $game = GroupGamePlayoff::with(['homeTeam.team', 'awayTeam.team'])
            ->find($gameId);
        if (is_null($game) || $game->playoff_pair_id !== $pairId || $game->playoffPair->tournament_id !== $tournamentId) {
            abort(404);
        }

        self::postToVk($game);

        return $this->renderAjax([], 'Результат игры опубликован');
    }
}
