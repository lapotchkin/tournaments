<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreGroupTournament;
use App\Http\Controllers\Controller;
use App\Models\GroupGamePlayoff;
use App\Models\GroupGamePlayoffPlayer;
use App\Models\GroupGameRegular;
use App\Models\GroupGameRegularPlayer;
use App\Models\GroupTournament;
use App\Models\GroupTournamentPlayoff;
use App\Models\GroupTournamentTeam;
use App\Models\GroupTournamentWinner;
use App\Models\Team;
use App\Models\TeamPlayer;
use Auth;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
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
 * Class GroupController
 *
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
     *
     * @return ResponseFactory|Response
     */
    public function create(StoreGroupTournament $request)
    {
        $validatedData = $request->validated();
        $tournament = new GroupTournament($validatedData);
        $tournament->save();

        return $this->renderAjax(['id' => $tournament->id]);
    }

    /**
     * @param StoreGroupTournament $request
     * @param GroupTournament      $groupTournament
     *
     * @return ResponseFactory|Response
     */
    public function edit(StoreGroupTournament $request, GroupTournament $groupTournament)
    {
        $validatedData = $request->validated();

        $groupTournament->platform_id = $validatedData['platform_id'];
        $groupTournament->app_id = $validatedData['app_id'];
        $groupTournament->title = $validatedData['title'];
        $groupTournament->playoff_rounds = $validatedData['playoff_rounds'];
        $groupTournament->min_players = $validatedData['min_players'];
        $groupTournament->thirdPlaceSeries = $validatedData['thirdPlaceSeries'];
        $groupTournament->vk_group_id = isset($validatedData['vk_group_id'])
            ? $validatedData['vk_group_id']
            : null;
        $groupTournament->startedAt = isset($validatedData['startedAt'])
            ? $validatedData['startedAt']
            : null;

        $groupTournament->save();

        return $this->renderAjax(['id' => $groupTournament->id]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function setWinner(Request $request, GroupTournament $groupTournament)
    {
        $validatedData = $request->validate(
            [
                'team_id' => 'required|int|min:0',
                'place'   => 'required|int|min:1|max:3',
            ]
        );
        $winner = GroupTournamentWinner::where('tournament_id', $groupTournament->id)
            ->where('place', $validatedData['place'])
            ->first();

        $message = $validatedData['place'] . ' место сохранено';
        if (is_null($winner)) {
            $validatedData['tournament_id'] = $groupTournament->id;
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
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return ResponseFactory|Response
     */
    public function addTeam(Request $request, GroupTournament $groupTournament)
    {
        $validatedData = $request->validate(
            [
                'team_id'  => 'required|int|exists:team,id',
                'division' => 'required|int|min:1|max:26',
            ]
        );

        $tournamentTeam = GroupTournamentTeam::withTrashed()
            ->where('tournament_id', $groupTournament->id)
            ->where('team_id', $validatedData['team_id'])
            ->first();

        if (is_null($tournamentTeam)) {
            $tournamentTeam = new GroupTournamentTeam;
            $tournamentTeam->tournament_id = $groupTournament->id;
            $tournamentTeam->team_id = $validatedData['team_id'];
            $tournamentTeam->division = $validatedData['division'];
            $tournamentTeam->save();
        } else {
            GroupTournamentTeam::withTrashed()
                ->where('tournament_id', $groupTournament->id)
                ->where('team_id', $validatedData['team_id'])
                ->update(
                    [
                        'division'  => $validatedData['division'],
                        'deletedAt' => null,
                    ]
                );
        }

        return $this->renderAjax();
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @param Team            $team
     *
     * @return ResponseFactory|Response
     */
    public function editTeam(Request $request, GroupTournament $groupTournament, Team $team)
    {
        $validatedData = $request->validate(
            [
                'division' => 'required|int|min:1|max:26',
            ]
        );

        GroupTournamentTeam::where('tournament_id', $groupTournament->id)
            ->where('team_id', $team->id)
            ->update(['division' => $validatedData['division']]);

        return $this->renderAjax();
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     * @param Team            $team
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteTeam(Request $request, GroupTournament $groupTournament, Team $team)
    {
        GroupTournamentTeam::where('tournament_id', $groupTournament->id)
            ->where('team_id', $team->id)
            ->delete();
        GroupGameRegular::whereTournamentId($groupTournament->id)
            ->where('home_team_id', '=', $team->id)
            ->orWhere('away_team_id', '=', $team->id)
            ->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return ResponseFactory|Response
     */
    public function editRegularGame(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $attributes = $groupGameRegular->attributesToArray();
        foreach ($validatedData['game'] as $field => $value) {
            if (!array_key_exists($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $groupGameRegular->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $groupGameRegular->{$field} = '00:' . $value;
            } else {
                $groupGameRegular->{$field} = $value;
            }
        }
        if (Auth::user()->isAdmin()) {
            $groupGameRegular->isConfirmed = 1;
        } else {
            $groupGameRegular->added_by = $groupGameRegular->getTeamId();
        }
        $groupGameRegular->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $groupGameRegular->id;
                    $player = GroupGameRegularPlayer::where('game_id', '=', $groupGameRegular->id)
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
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function resetRegularGame(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $emptyGame = self::GAME_EMPTY;
        $emptyGame['isShootout'] = 0;
        $groupGameRegular->fill($emptyGame);
        $groupGameRegular->save();
        foreach ($groupGameRegular->protocols as $protocol) {
            $protocol->delete();
        }

        return $this->renderAjax([], 'Протокол игры обнулён');
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return ResponseFactory|Response
     */
    public function createRegularProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $protocol = new GroupGameRegularPlayer;
        $protocol->fill($input);
        $protocol->save();

        return $this->renderAjax(['id' => $protocol->id]);
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupGameRegular       $groupGameRegular
     * @param GroupGameRegularPlayer $groupGameRegular_player
     *
     * @return ResponseFactory|Response
     */
    public function updateRegularProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular,
        GroupGameRegularPlayer $groupGameRegular_player
    )
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $groupGameRegular_player->fill($input);
        $groupGameRegular_player->save();

        return $this->renderAjax();
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupGameRegular       $groupGameRegular
     * @param GroupGameRegularPlayer $groupGameRegular_player
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deleteRegularProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular,
        GroupGameRegularPlayer $groupGameRegular_player
    )
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $groupGameRegular_player->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPair(Request $request, GroupTournament $groupTournament)
    {
        $input = json_decode($request->getContent(), true);
        $rules = [
            'round'       => 'required|int',
            'pair'        => 'required|int',
            'team_one_id' => 'int|exists:team,id',
            'team_two_id' => 'int|exists:team,id',
        ];
        $validator = Validator::make($input, $rules);
        $validatedData = $validator->validate();
        $validatedData['tournament_id'] = $groupTournament->id;

        if (!isset($validatedData['team_one_id']) && !isset($validatedData['team_two_id'])) {
            abort(400, 'Не передан ни один ID команды');
        }

        /** @var GroupTournamentPlayoff $groupTournamentPlayoff */
        $groupTournamentPlayoff = GroupTournamentPlayoff::where('tournament_id', '=', $groupTournament->id)
            ->where('round', '=', $validatedData['round'])
            ->where('pair', '=', $validatedData['pair'])
            ->first();

        if (is_null($groupTournamentPlayoff)) {
            $groupTournamentPlayoff = new GroupTournamentPlayoff;
        }
        $groupTournamentPlayoff->fill($validatedData);
        $groupTournamentPlayoff->save();

        return $this->renderAjax(['id' => $groupTournamentPlayoff->id], 'Пара создана');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function updatePair(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff
    )
    {
        if ($groupTournamentPlayoff->tournament_id !== $groupTournament->id) {
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

        $groupTournamentPlayoff->fill($validatedData);
        $groupTournamentPlayoff->save();

        return $this->renderAjax([], 'Пара обновлена');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function createPlayoffGame(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff
    )
    {
        if ($groupTournamentPlayoff->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $groupGamePlayoff = new GroupGamePlayoff;
        $attributes = $groupGamePlayoff->getFillable();
        foreach ($validatedData['game'] as $field => $value) {
            if (!in_array($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $groupGamePlayoff->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $groupGamePlayoff->{$field} = '00:' . $value;
            } else {
                $groupGamePlayoff->{$field} = $value;
            }
        }
        $groupGamePlayoff->playoff_pair_id = $groupTournamentPlayoff->id;
        $groupGamePlayoff->home_team_id = $groupTournamentPlayoff->team_one_id;
        $groupGamePlayoff->away_team_id = $groupTournamentPlayoff->team_two_id;

        $groupGamePlayoff->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $groupGamePlayoff->id;
                    $player = GroupGamePlayoffPlayer::where('game_id', '=', $groupGamePlayoff->id)
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

        return $this->renderAjax(['id' => $groupGamePlayoff->id], 'Игра добавлена');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function editPlayoffGame(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    )
    {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, self::GAME_RULES);
        $validatedData = $validator->validate();

        $attributes = $groupGamePlayoff->getFillable();
        foreach ($validatedData['game'] as $field => $value) {
            if (!in_array($field, $attributes)) {
                continue;
            }

            if ($value === '') {
                $groupGamePlayoff->{$field} = null;
            } elseif (strstr($field, '_time')) {
                $groupGamePlayoff->{$field} = '00:' . $value;
            } else {
                $groupGamePlayoff->{$field} = $value;
            }
        }
        $groupGamePlayoff->save();

        if (isset($input['players'])) {
            foreach ($input['players'] as $side => $players) {
                foreach ($players as $playerData) {
                    $playerData['game_id'] = $groupGamePlayoff->id;
                    $player = GroupGamePlayoffPlayer::where('game_id', '=', $groupGamePlayoff->id)
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
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function resetPlayoffGame(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    )
    {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $groupGamePlayoff->fill(self::GAME_EMPTY);
        $groupGamePlayoff->save();
        foreach ($groupGamePlayoff->protocols as $protocol) {
            $protocol->delete();
        }

        return $this->renderAjax([], 'Протокол игры обнулён');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     *
     * @return ResponseFactory|Response
     */
    public function createPlayoffProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    )
    {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $groupGamePlayoff_player = new GroupGamePlayoffPlayer;
        $groupGamePlayoff_player->fill($input);
        $groupGamePlayoff_player->save();

        return $this->renderAjax(['id' => $groupGamePlayoff_player->id]);
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     * @param GroupGamePlayoffPlayer $groupGamePlayoff_player
     *
     * @return ResponseFactory|Response
     */
    public function updatePlayoffProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff,
        GroupGamePlayoffPlayer $groupGamePlayoff_player
    )
    {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $input = json_decode($request->getContent(), true);
        $groupGamePlayoff_player->fill($input);
        $groupGamePlayoff_player->save();

        return $this->renderAjax();
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     * @param GroupGamePlayoffPlayer $groupGamePlayoff_player
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayoffProtocol(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff,
        GroupGamePlayoffPlayer $groupGamePlayoff_player
    )
    {
        $groupGamePlayoff->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $groupGamePlayoff_player->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
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
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load(['homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        self::postToVk($groupGameRegular);

        return $this->renderAjax([], 'Результат игры опубликован');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
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
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    )
    {
        $groupGamePlayoff->load(['homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        self::postToVk($groupGamePlayoff);

        return $this->renderAjax([], 'Результат игры опубликован');
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     */
    public function confirmRegularResult(
        Request $request,
        GroupTournament $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->isConfirmed = 1;
        $groupGameRegular->save();

        return $this->renderAjax([], 'Результат игры подтверждены');
    }

    /**
     * @param Request                $request
     * @param GroupTournament        $groupTournament
     * @param GroupTournamentPlayoff $groupTournamentPlayoff
     * @param GroupGamePlayoff       $groupGamePlayoff
     *
     * @return ResponseFactory|Response
     */
    public function confirmPlayoffResult(
        Request $request,
        GroupTournament $groupTournament,
        GroupTournamentPlayoff $groupTournamentPlayoff,
        GroupGamePlayoff $groupGamePlayoff
    )
    {
        $groupGamePlayoff->load(['homeTeam.team', 'awayTeam.team']);
        if (
            $groupGamePlayoff->playoff_pair_id !== $groupTournamentPlayoff->id
            || $groupGamePlayoff->playoffPair->tournament_id !== $groupTournament->id
        ) {
            abort(404);
        }

        $groupGamePlayoff->isConfirmed = 1;
        $groupGamePlayoff->save();

        return $this->renderAjax([], 'Результат игры подтверждены');
    }
}
