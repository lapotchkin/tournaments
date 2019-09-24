<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamPlayer;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    const TEAM_RULES = [
        'name'        => 'required|string',
        'short_name'  => 'required|string',
        'platform_id' => 'required|string|exists:platform,id',
    ];

    /**
     * @param StoreRequest $request
     * @return ResponseFactory|Response
     */
    public function create(StoreRequest $request)
    {
        $validatedData = $request->validate(self::TEAM_RULES);
        /** @var Team|null $team */
        $team = Team::withTrashed()
            ->whereName($validatedData['name'])
            ->wherePlatformId($validatedData['platform_id'])
            ->first();
        if (!is_null($team)) {
            //Восстановить команду, если его удалили
            if ($team->deletedAt) {
                $team->restore();
                $team->fill($validatedData);
                $team->save();

                return $this->renderAjax(['id' => $team->id]);
            }

            abort(409, 'Такая команда уже существует');
        }

        $team = new Team;
        $team->fill($validatedData);
        $team->save();

        return $this->renderAjax(['id' => $team->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $teamId
     * @return ResponseFactory|Response
     */
    public function edit(StoreRequest $request, int $teamId)
    {
        $validatedData = $request->validate(self::TEAM_RULES);
        /** @var Team|null $team */
        $team = Team::find($teamId);
        if (is_null($team)) {
            abort(404, 'Команда не найдена');
        }

        $team->fill($validatedData);
        $team->save();

        return $this->renderAjax(['id' => $team->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $teamId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(StoreRequest $request, int $teamId)
    {
        /** @var Team|null $team */
        $team = Team::find($teamId);
        if (is_null($team)) {
            abort(404, 'Команда не найдена');
        }

        $team->delete();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $teamId
     * @return ResponseFactory|Response
     */
    public function addPlayer(StoreRequest $request, int $teamId)
    {
        $validatedData = $request->validate([
            'player_id' => 'required|int|exists:player,id',
        ]);
        $validatedData['team_id'] = $teamId;
        $teamPlayer = TeamPlayer::whereTeamId($validatedData['team_id'])
            ->wherePlayerId($validatedData['player_id'])
            ->first();
        if (!is_null($teamPlayer)) {
            abort(409, 'Игрок уже в команде');
        }

        $teamPlayer = new TeamPlayer($validatedData);
        $teamPlayer->fill($validatedData);
        $teamPlayer->save();

        return $this->renderAjax();
    }

    /**
     * @param StoreRequest $request
     * @param int          $teamId
     * @param int          $playerId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function deletePlayer(StoreRequest $request, int $teamId, int $playerId)
    {
        TeamPlayer::whereTeamId($teamId)
            ->wherePlayerId($playerId)
            ->delete();

        return $this->renderAjax();
    }
}
