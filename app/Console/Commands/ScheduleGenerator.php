<?php

namespace App\Console\Commands;

use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\Player;
use App\Models\Team;
use Exception;
use Illuminate\Console\Command;
use Storage;

class ScheduleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:generate {type} {id} {gamesCount=2} {rounds=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate schedule';

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
     * @throws Exception
     */
    public function handle()
    {
        $tournament = $this->argument('type') === 'personal'
            ? PersonalTournament::with(['regularGames'])->find($this->argument('id'))
            : GroupTournament::with(['regularGames'])->find($this->argument('id'));
        if (is_null($tournament)) {
            $this->error("Турнир не найден");
            return;
        }

        $this->info("Генерирую расписание для турнира {$this->argument('id')} ({$this->argument('type')}) {$tournament->title}");

        $scheduleToImport = Storage::disk('local')
            ->get("schedules/{$this->argument('type')}/{$this->argument('id')}.txt");
        $lines = explode("\n", trim($scheduleToImport));
        $lastRound = 0;
        $linesCount = count($lines);
        for ($round = 1; $round < (int)$this->argument('rounds') + 1; $round += 1) {
            for ($i = 0; $i < $linesCount; $i += 1) {
                $line = $lines[$i];
                if (!$line) {
                    continue;
                }

                $data = explode('|', $line);
                $this->info("Игра " . ($data[0] * $round));
                $data[1] = (int)$data[1] + $lastRound;

                if ($this->argument('type') === 'personal') {
                    $this->_createPersonalGame(
                        $this->argument('id'),
                        $data,
                        (int)$this->argument('gamesCount'),
                        $round
                    );
                } else {
                    $this->_createGroupGame(
                        $this->argument('id'),
                        $data,
                        (int)$this->argument('gamesCount'),
                        $round
                    );
                }
                if ($linesCount === $i + 1) {
                    $lastRound = (int)$data[1];
                }
            }
        }
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $gamesCount
     * @param int $round
     * @throws Exception
     */
    private function _createPersonalGame($id, $data, $gamesCount = 2, $round = 1)
    {
        $homePLayer = Player::whereTag($data[5])->first();
        if (is_null($homePLayer)) {
            throw new Exception("Хозяин не найден");
        }
        $this->info("    Хозяин: {$homePLayer->tag} ({$homePLayer->name})");

        $awayPLayer = Player::whereTag($data[6])->first();
        if (is_null($awayPLayer)) {
            throw new Exception("Гость не найден");
        }
        $this->info("    Гость: {$awayPLayer->tag} ({$awayPLayer->name})");

        if ($gameOne = $this->_searchGame($homePLayer->id, $awayPLayer->id, $round)) {
            $this->info("   Обновление игры 1");
            $gameOne->round = (int)$data[1];
        } else {
            $this->info("   Создание игры 1");
            $gameOne = new PersonalGameRegular([
                'tournament_id'  => (int)$id,
                'round'          => (int)$data[1],
                'home_player_id' => $homePLayer->id,
                'away_player_id' => $awayPLayer->id,
            ]);
        }
        $gameOne->save();

        if ($gamesCount === 2) {
            if ($gameTwo = $this->_searchGame($awayPLayer->id, $homePLayer->id, $round)) {
                $this->info("   Обновление игры 2");
                $gameTwo->round = (int)$data[1];
            } else {
                $this->info("   Создание игры 2");
                $gameTwo = new PersonalGameRegular([
                    'tournament_id'  => (int)$id,
                    'round'          => (int)$data[1],
                    'away_player_id' => $homePLayer->id,
                    'home_player_id' => $awayPLayer->id,
                ]);
            }
            $gameTwo->save();
        }
    }

    /**
     * @param int  $firstId
     * @param int  $secondId
     * @param int  $round
     * @param bool $isPersonal
     * @return PersonalGameRegular|GroupGameRegular|null
     */
    private function _searchGame(int $firstId, int $secondId, int $round, bool $isPersonal = true)
    {
        $games = $isPersonal
            ? PersonalGameRegular::whereHomePlayerId($firstId)->whereAwayPlayerId($secondId)
            : GroupGameRegular::whereHomeTeamId($firstId)->whereAwayTeamId($secondId);
        $game = $games->limit(1)->offset($round - 1)->first();

        return $game;
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $gamesCount
     * @param int $round
     * @throws Exception
     */
    private function _createGroupGame($id, $data, $gamesCount = 2, $round = 1)
    {
        $homeTeam = Team::whereName($data[5])->first();
        if (is_null($homeTeam)) {
            throw new Exception("Хозяин не найден");
        }
        $this->info("    Хозяин: {$homeTeam->name}");

        $awayTeam = Team::whereName($data[6])->first();
        if (is_null($awayTeam)) {
            throw new Exception("Гость не найден");
        }
        $this->info("    Гость: {$awayTeam->name}");

        if ($gameOne = $this->_searchGame($homeTeam->id, $awayTeam->id, $round)) {
            $this->info("   Обновление игры 1");
            $gameOne->round = (int)$data[1];
        } else {
            $this->info("   Создание игры 1");
            $gameOne = new GroupGameRegular([
                'tournament_id'  => (int)$id,
                'round'          => (int)$data[1],
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
            ]);
        }
        $gameOne->save();

        if ($gamesCount === 2) {
            if ($gameTwo = $this->_searchGame($awayTeam->id, $homeTeam->id, $round)) {
                $this->info("   Обновление игры 2");
                $gameTwo->round = (int)$data[1];
            } else {
                $this->info("   Создание игры 2");
                $gameTwo = new GroupGameRegular([
                    'tournament_id'  => (int)$id,
                    'round'          => (int)$data[1],
                    'away_team_id' => $homeTeam->id,
                    'home_team_id' => $awayTeam->id,
                ]);
            }
            $gameTwo->save();
        }
    }
}
