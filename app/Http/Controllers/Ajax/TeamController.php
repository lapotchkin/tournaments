<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\AppTeam;
use App\Models\Player;
use App\Models\Team;
use App\Models\TeamManagement;
use App\Models\TeamPlayer;
use Auth;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    protected const ADD_TO_TEAM = 1;
    protected const DELETE_FROM_TEAM = 2;
    protected const SET_AS_CAPTAIN = 3;
    protected const SET_AS_ASSISTANT = 4;
    protected const SET_AS_PLAYER = 5;

    protected const TEAM_RULES = [
        'name'        => 'required|string',
        'short_name'  => 'required|string|max:3',
        'platform_id' => 'required|string|exists:platform,id',
    ];

    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate(self::TEAM_RULES);
        /** @var Team|null $team */
        $team = Team::withTrashed()
            ->whereName($validatedData['name'])
            ->wherePlatformId($validatedData['platform_id'])
            ->first();
        if (!is_null($team)) {
            //Восстановить команду, если её удалили
            if ($team->deletedAt) {
                $team->restore();
                $team->fill($validatedData);
                $team->save();

                return $this->renderAjax(['id' => $team->id]);
            }

            abort(409, 'Такая команда уже существует');
        }

        $team = new Team();
        $team->fill($validatedData);
        $team->save();

        return $this->renderAjax(['id' => $team->id]);
    }

    /**
     * @param Request $request
     * @param Team    $team
     *
     * @return ResponseFactory|Response
     */
    public function edit(Request $request, Team $team)
    {
        $validatedData = $request->validate(self::TEAM_RULES);
        $team->fill($validatedData);
        $team->save();

        return $this->renderAjax(['id' => $team->id]);
    }

    /**
     * @param Request $request
     * @param Team    $team
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(Request $request, Team $team)
    {
        $team->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param Team    $team
     *
     * @return ResponseFactory|Response
     */
    public function setTeamId(Request $request, Team $team)
    {
        $validatedData = $request->validate([
            'app_id'      => 'required|string|exists:app,id',
            'app_team_id' => 'required|int',
        ]);
        /** @var AppTeam|null $appTeam */
        $appTeam = AppTeam::where(['app_id' => $validatedData['app_id'], 'team_id' => $team->id])->first();
        if (!is_null($appTeam)) {
            $appTeam->app_team_id = $validatedData['app_team_id'];
        } else {
            $validatedData['team_id'] = $team->id;
            $appTeam = new AppTeam($validatedData);
        }

        $appTeam->save();

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param Team    $team
     * @param App     $app
     *
     * @return ResponseFactory|Response
     */
    public function deleteTeamId(Request $request, Team $team, App $app)
    {
        AppTeam::where(['app_id' => $app->id, 'team_id' => $team->id])->delete();

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param Team    $team
     *
     * @return ResponseFactory|Response
     */
    public function addPlayer(Request $request, Team $team)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|exists:player,id',
        ]);
        $validatedData['team_id'] = $team->id;
        $teamPlayer = TeamPlayer::whereTeamId($validatedData['team_id'])
            ->wherePlayerId($validatedData['player_id'])
            ->first();
        if (!is_null($teamPlayer)) {
            abort(409, 'Игрок уже в команде');
        }

        $teamPlayer = new TeamPlayer($validatedData);
        $teamPlayer->fill($validatedData);
        $teamPlayer->save();
        $this->_createAction($teamPlayer->team_id, $teamPlayer->player_id, self::ADD_TO_TEAM);

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param Team    $team
     * @param Player  $player
     *
     * @return ResponseFactory|Response
     */
    public function updatePlayer(Request $request, Team $team, Player $player)
    {
        $validatedData = $request->validate([
            'isCaptain' => 'required|int|min:0|max:2',
        ]);

        $teamPlayer = TeamPlayer::whereTeamId($team->id)
            ->wherePlayerId($player->id)
            ->first();

        if (is_null($teamPlayer)) {
            abort(404);
        }

        if ($validatedData['isCaptain'] === '1') {
            foreach ($team->teamPlayers as $player) {
                if ($player->isCaptain === 1) {
                    $player->isCaptain = 0;
                    $player->save();
                    $this->_createAction($player->team_id, $player->player_id, self::SET_AS_PLAYER);
                }
            }
        }

        $teamPlayer->isCaptain = $validatedData['isCaptain'];
        $teamPlayer->save();
        $this->_createAction($teamPlayer->team_id, $teamPlayer->player_id, $this->_getActionId($teamPlayer->isCaptain));

        return $this->renderAjax();
    }

    /**
     * @param Request $request
     * @param Team    $team
     * @param Player  $player
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayer(Request $request, Team $team, Player $player)
    {
        TeamPlayer::whereTeamId($team->id)
            ->wherePlayerId($player->id)
            ->delete();
        $this->_createAction($team->id, $player->id, self::DELETE_FROM_TEAM);

        return $this->renderAjax();
    }

    /**
     * @param int $teamId
     * @param int $playerId
     * @param int $actionId
     */
    private function _createAction(int $teamId, int $playerId, int $actionId)
    {
        $action = new TeamManagement([
            'team_id'    => $teamId,
            'manager_id' => Auth::id(),
            'player_id'  => $playerId,
            'action_id'  => $actionId,
        ]);
        $action->save();
    }

    /**
     * @param int $isCaptain
     *
     * @return int
     */
    private function _getActionId(int $isCaptain)
    : int
    {
        switch ($isCaptain) {
            case 1:
                return self::SET_AS_CAPTAIN;
            case 2:
                return self::SET_AS_ASSISTANT;
            default:
                return self::SET_AS_PLAYER;
        }
    }
}
