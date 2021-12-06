<?php

namespace App\Http\Controllers\Site;

use App\Models\EaGame;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\GroupTournamentGoalies;
use App\Models\GroupTournamentLeaders;
use App\Models\GroupTournamentPosition;
use App\Models\PlayerPosition;
use App\Utils\GroupGamesHelper;
use App\Utils\TournamentResults;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class GroupRegularController
 *
 * @package App\Http\Controllers\Site
 */
class GroupRegularController extends Controller
{
    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     * @throws Exception
     */
    public function index(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['winners.team']);
        $toDate = $request->input('toDate');

        $firstPlayedGameDate = GroupTournamentPosition::readFirstGameDate($groupTournament->id);
        $dateToCompare = !is_null($toDate)
            ? $toDate . ' 00:00:00'
            : GroupTournamentPosition::readLastGameDate($groupTournament->id);

        $currentPosition = GroupTournamentPosition::readPosition($groupTournament->id);
        $previousPosition = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousPosition = GroupTournamentPosition::readPosition($groupTournament->id, $dateToCompare);
        }
        $positions = TournamentResults::getPosition($currentPosition, $previousPosition);

        $currentLeaders = GroupTournamentLeaders::readLeaders($groupTournament->id);
        $previousLeaders = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousLeaders = GroupTournamentLeaders::readLeaders($groupTournament->id, $dateToCompare);
        }
        $leaders = TournamentResults::getLeaders($currentLeaders, $previousLeaders);

        $currentGoalies = GroupTournamentGoalies::readGoalies($groupTournament->id);
        $previousGoalies = null;
        if (!is_null($firstPlayedGameDate) && !is_null($dateToCompare) && $dateToCompare >= $firstPlayedGameDate) {
            $previousGoalies = GroupTournamentGoalies::readGoalies($groupTournament->id, $dateToCompare);
        }
        $goalies = TournamentResults::getGoalies($currentGoalies, $currentPosition, $previousGoalies, $previousPosition);

        $divisions = [];
        foreach ($positions as $position) {
            $divisions[$position->division][] = $position;
        }

        return view('site.group.regular.index', [
            'tournament'    => $groupTournament,
            'divisions'     => $divisions,
            'leaders'       => $leaders,
            'goalies'       => $goalies['top'],
            'goaliesAll'    => $goalies['all'],
            'dateToCompare' => $dateToCompare,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     * @throws Exception
     */
    public function games(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['regularGames.homeTeam.team', 'regularGames.awayTeam.team', 'winners.team']);
        $rounds = [];
        $divisions = [];
        foreach ($groupTournament->regularGames as $regularGame) {
            $division = $regularGame->homeTeam->division;
            if (!in_array($division, $divisions)) {
                $divisions[] = $division;
            }
            $rounds[$regularGame->round][$division][] = $regularGame;

            if (is_null($regularGame->match_id)) {
                $regularGame->gamePlayed = null;
                $game = EaGame::where(
                    'clubs.' . $regularGame->homeTeam->team->getClubId($groupTournament->app_id),
                    'exists',
                    true
                )
                    ->where(
                        'clubs.' . $regularGame->awayTeam->team->getClubId($groupTournament->app_id),
                        'exists',
                        true
                    )
                    ->where(
                        'timestamp',
                        '>',
                        $groupTournament->startedAt ? $groupTournament->startedAt->getTimestamp() : 0
                    )
                    ->orderByDesc('timestamp')
                    ->first();
                if (!is_null($game)) {
                    $date = new DateTime();
                    $date->setTimestamp($game->timestamp);
                    $regularGame->gamePlayed = $date->format('d.m H:i');
                }
            }
        }

        return view('site.group.regular.games', [
            'tournament' => $groupTournament,
            'rounds'     => $rounds,
            'divisions'  => $divisions,
        ]);
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return Factory|View
     */
    public function game(Request $request, GroupTournament $groupTournament, GroupGameRegular $groupGameRegular)
    {
        $groupGameRegular->load(['protocols.player', 'homeTeam.team', 'awayTeam.team']);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        GroupGamesHelper::setProtocols($groupGameRegular);

        return view('site.group.game_protocol', [
            'title' => $groupGameRegular->homeTeam->team->name
                . ' vs. ' . $groupGameRegular->awayTeam->team->name . ' (Тур ' . $groupGameRegular->round . ')',
            'game'  => $groupGameRegular,
            'stars' => $groupGameRegular->getStars(),
        ]);
    }

    /**
     * @param Request          $request
     * @param GroupTournament  $groupTournament
     * @param GroupGameRegular $groupGameRegular
     *
     * @return Factory|View
     */
    public function gameEdit(
        Request          $request,
        GroupTournament  $groupTournament,
        GroupGameRegular $groupGameRegular
    )
    {
        $groupGameRegular->load([
            'protocols.player',
            'protocols.playerPosition',
            'homeTeam.team.players',
            'awayTeam.team.players',
        ]);
        if ($groupGameRegular->tournament_id !== $groupTournament->id) {
            abort(404);
        }

        foreach ($groupGameRegular->protocols as $protocol) {
            if ($protocol->team_id === $groupGameRegular->home_team_id) {
                $groupGameRegular->homeProtocols[] = $protocol;
            } else {
                $groupGameRegular->awayProtocols[] = $protocol;
            }
        }
        $protocols = $groupGameRegular->getSafeProtocols();
        $players = $groupGameRegular->getSafePlayersData();
        $positionsRaw = PlayerPosition::all();
        $positions = [];
        foreach ($positionsRaw as $position) {
            $positions[] = $position->getSafePosition();
        }

        return view('site.group.game_form', [
            'title'     => $groupGameRegular->homeTeam->team->name
                . ' vs. ' . $groupGameRegular->awayTeam->team->name . ' (Тур ' . $groupGameRegular->round . ')',
            'pair'      => null,
            'game'      => $groupGameRegular,
            'protocols' => $protocols,
            'players'   => $players,
            'positions' => $positions,
        ]);
    }

    /**
     * @param Request         $request
     * @param GroupTournament $groupTournament
     *
     * @return Factory|View
     */
    public function schedule(Request $request, GroupTournament $groupTournament)
    {
        $groupTournament->load(['regularGames.homeTeam.team', 'regularGames.awayTeam.team', 'winners.team']);
        $rounds = [];
        foreach ($groupTournament->regularGames as $index => $regularGame) {
            if (!isset($groupTournament->regularGames[$index + 1])) {
                $rounds[$regularGame->round][$regularGame->homeTeam->division][] = $regularGame;
                continue;
            }

            if (
                $groupTournament->regularGames[$index + 1]->home_team_id !== $groupTournament->regularGames[$index]->away_team_id
                || $groupTournament->regularGames[$index + 1]->away_team_id !== $groupTournament->regularGames[$index]->home_team_id
            ) {
                $rounds[$regularGame->round][$regularGame->homeTeam->division][] = $regularGame;
            }
        }

        return view('site.group.regular.schedule', [
            'tournament' => $groupTournament,
            'rounds'     => $rounds,
        ]);
    }
}
