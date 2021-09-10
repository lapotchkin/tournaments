<?php

namespace App\Console\Commands;

use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Utils\TournamentScheduler;
use Exception;
use Illuminate\Console\Command;

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
     * @var PersonalTournament|GroupTournament
     */
    protected $tournament;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ((int)$this->argument('gamesCount') > 2) {
            $this->error("Параметр 'gamesCount' не должен быть больше 2");
            return;
        }

        $this->tournament = $this->argument('type') === 'personal'
            ? PersonalTournament::with(['regularGames'])->find($this->argument('id'))
            : GroupTournament::with(['regularGames'])->find($this->argument('id'));

        if (is_null($this->tournament)) {
            $this->error("Турнир не найден");
            return;
        }

        if (count($this->tournament->regularGames)) {
            $this->error("Расписание для турнира {$this->tournament->title} уже создано");
            return;
        }

        $this->info("Генерирую расписание для турнира {$this->argument('id')} ({$this->argument('type')}) {$this->tournament->title}");

        if ($this->argument('type') === 'personal') {
            $this->generatePersonalSchedule();
        } else {
            $this->generateGroupSchedule();
        }

        $this->info('Расписание сгенерировано');
    }

    protected function generatePersonalSchedule()
    {
        $divisions = [];
        foreach ($this->tournament->tournamentPlayers as $tournamentPlayer) {
            $divisions[$tournamentPlayer->division][] = $tournamentPlayer->player_id;
        }

        foreach ($divisions as $division) {
            $divisionSchedule = TournamentScheduler::generate($division);

            for ($repeat = 1; $repeat < (int)$this->argument('rounds') + 1; $repeat += 1) {
                foreach ($divisionSchedule as $round => $games) {
                    foreach ($games as $players) {
                        $gameOne = new PersonalGameRegular([
                            'tournament_id'  => $this->tournament->id,
                            'round'          => ($round + 1) * $repeat,
                            'home_player_id' => $players[0],
                            'away_player_id' => $players[1],
                        ]);
                        $gameOne->save();

                        if ((int)$this->argument('gamesCount') === 2) {
                            $gameTwo = new PersonalGameRegular([
                                'tournament_id'  => $this->tournament->id,
                                'round'          => ($round + 1) * $repeat,
                                'home_player_id' => $players[1],
                                'away_player_id' => $players[0],
                            ]);
                            $gameTwo->save();
                        }
                    }
                }
            }
        }
    }

    protected function generateGroupSchedule()
    {
        $divisions = [];
        foreach ($this->tournament->tournamentTeams as $tournamentTeam) {
            $divisions[$tournamentTeam->division][] = $tournamentTeam->team_id;
        }

        foreach ($divisions as $division) {
            $divisionSchedule = TournamentScheduler::generate($division);

            for ($repeat = 1; $repeat < (int)$this->argument('rounds') + 1; $repeat += 1) {
                foreach ($divisionSchedule as $round => $games) {
                    foreach ($games as $teams) {
                        $gameOne = new GroupGameRegular([
                            'tournament_id' => $this->tournament->id,
                            'round'         => ($round + 1) * $repeat,
                            'home_team_id'  => $teams[0],
                            'away_team_id'  => $teams[1],
                        ]);
                        $gameOne->save();

                        if ((int)$this->argument('gamesCount') === 2) {
                            $gameTwo = new GroupGameRegular([
                                'tournament_id' => $this->tournament->id,
                                'round'         => ($round + 1) * $repeat,
                                'home_team_id'  => $teams[1],
                                'away_team_id'  => $teams[0],
                            ]);
                            $gameTwo->save();
                        }
                    }
                }
            }
        }
    }
}
