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

class ScheduleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:generate {type} {id} {gamesCount=2}';

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
            ? PersonalTournament::find($this->argument('id'))
            : GroupTournament::find($this->argument('id'));
        if (is_null($tournament)) {
            throw new Exception("Турнир не найден", 65);
        }

        $this->info("Генерирую расписание для турнира {$this->argument('id')} ({$this->argument('type')}) {$tournament->title}");
        if (count($tournament->regularGames)) {
            throw new Exception("Расписание уже составлено", 65);
        }

        $filePath = storage_path("schedules/{$this->argument('type')}/{$this->argument('id')}.txt");
        if (!is_readable($filePath)) {
            throw new Exception("Файл не читается", 72);
        }

        $lines = file($filePath);
        foreach ($lines as $line) {
            $data = explode('|', $line);
            $this->info("Игра {$data[0]}");

            if ($this->argument('type') === 'personal') {
                $this->_createPersonalGame(
                    $this->argument('id'),
                    $data,
                    $this->argument('gamesCount')
                );
            } else {
                $this->_createGroupGame(
                    $this->argument('id'),
                    $data,
                    $this->argument('gamesCount')
                );
            }
        }
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $gamesCount
     * @throws Exception
     */
    private function _createPersonalGame($id, $data, $gamesCount = 2)
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

        $gameOne = new PersonalGameRegular([
            'tournament_id'  => (int)$id,
            'round'          => (int)$data[1],
            'home_player_id' => $homePLayer->id,
            'away_player_id' => $awayPLayer->id,
        ]);
        $gameOne->save();

        if ($gamesCount === 2) {
            $gameTwo = new PersonalGameRegular([
                'tournament_id'  => (int)$id,
                'round'          => (int)$data[1],
                'away_player_id' => $homePLayer->id,
                'home_player_id' => $awayPLayer->id,
            ]);
            $gameTwo->save();
        }
    }

    /**
     * @param     $id
     * @param     $data
     * @param int $gamesCount
     * @throws Exception
     */
    private function _createGroupGame($id, $data, $gamesCount = 2)
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
        $this->info("    Гость: {$homeTeam->name}");

        $gameOne = new GroupGameRegular([
            'tournament_id' => (int)$id,
            'round'         => (int)$data[1],
            'home_team_id'  => $homeTeam->id,
            'away_team_id'  => $awayTeam->id,
        ]);
        $gameOne->save();

        if ($gamesCount === 2) {
            $gameTwo = new GroupGameRegular([
                'tournament_id' => (int)$id,
                'round'         => (int)$data[1],
                'away_team_id'  => $homeTeam->id,
                'home_team_id'  => $awayTeam->id,
            ]);
            $gameTwo->save();
        }
    }
}
