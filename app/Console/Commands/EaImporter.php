<?php

namespace App\Console\Commands;

use App\Models\EaGame;
use App\Models\EaRest;
use App\Models\GroupTournament;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class EaImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ea:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import games from EA API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws GuzzleException
     */
    public function handle()
    {
        /**
         * @var GroupTournament[] $tournaments
         */
        $tournaments = GroupTournament::all();
        foreach ($tournaments as $tournament) {
            if (count($tournament->winners)) {
                continue;
            }

            $this->line('Import data for tournament ID: ' . $tournament->id);
            $platform = $tournament->platform_id === 'playstation4' ? 'ps4' : $tournament->platform_id;
            $teams = [];
            $clubIds = [];
            foreach ($tournament->teams as $team) {
                $clubId = $team->getClubId($tournament->app_id);
                $teams[$team->id] = [
                    'clubId' => $clubId,
                    'name'   => $team->name,
                ];
                $clubIds[] = $clubId;
            }

            foreach ($teams as $teamId => $team) {
                $this->line("  Team ID: {$teamId} {$team['name']} ({$team['clubId']})");

                $this->line("  PRIVATE");
                $privateGames = json_decode(
                    EaRest::readGames($platform, $tournament->app_id, $team['clubId']),
                    true
                );
                $this->saveGames($privateGames, $clubIds);

                $this->line("  REGULAR");
                $regularGames = json_decode(
                    EaRest::readGames($platform, $tournament->app_id, $team['clubId'], false),
                    true
                );
                $this->saveGames($regularGames, $clubIds);
            }
        }
    }

    protected function saveGames(array $games, array $clubIds) {
        foreach ($games as $game) {
            $gameClubIds = array_keys($game['clubs']);
            if (count($gameClubIds) < 2) {
                $this->warn("    Match ID: {$game['matchId']}. Less than 2 teams in the match");
                continue;
            }

            if (!in_array($gameClubIds[0], $clubIds) || !in_array($gameClubIds[1], $clubIds)) {
                $this->warn("    Match ID: {$game['matchId']}. One of the teams is not presented in the tournament");
                continue;
            }

            /**
             * @var EaGame $eaGame
             */
            $eaGame = EaGame::where('matchId', '=', $game['matchId'])->first();

            if (!is_null($eaGame)) {
                $this->warn("    Match ID: {$game['matchId']}. Game already exists");
                continue;
            }

            $this->line("    Match ID: {$game['matchId']}. Creating the game");
            $eaGame = new EaGame($game);
            $eaGame->save();
        }
    }
}
