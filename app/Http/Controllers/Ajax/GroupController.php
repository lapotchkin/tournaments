<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreGroupTournament;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\GroupGameRegular;
use App\Models\GroupGameRegularPlayer;
use App\Models\GroupTournament;
use App\Models\GroupTournamentTeam;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Validator;

/**
 * Class GroupController
 * @package App\Http\Controllers\Ajax
 */
class GroupController extends Controller
{
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
        $rules = [
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
        $validator = Validator::make($input, $rules);
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

        $game->fill([
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
            'isShootout'            => 0,
            'isTechnicalDefeat'     => 0,
            'match_id'              => null,
        ]);
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
}
