<?php

namespace App\Console\Commands;

use App\Models\GroupTournament;
use Exception;
use Illuminate\Console\Command;
use Storage;

class NhlGamerScheduleConverter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:convert {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert NHL Gamer schedule to our format';

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
        $tournament = GroupTournament::find($this->argument('id'));
        if (is_null($tournament)) {
            throw new Exception("Турнир не найден", 65);
        }

        $this->info("Генерирую шаблон расписания для турнира {$this->argument('id')} {$tournament->title}");

        $scheduleToImport = Storage::disk('local')->get("schedules/nhlgamer/{$this->argument('id')}.txt");
        $lines = explode("\n", $scheduleToImport);
        $i = 1;
        $round = 0;
        $teamsRound = [];
        $schedule = '';
        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }

            $data = explode('	', $line);

            if ($data[0] === 'AWAY' || $data[0] === 'HOME') {
                $round += 1;
                $teamsRound[$round] = [];
                continue;
            }

            $data[2] = trim($data[2]);
            if (in_array($data[2], $teamsRound[$round])) {
                continue;
            }

            $teamsRound[$round][] = $data[0];
            $string = "{$i}|{$round}|Регулярный чемпионат|{$round}-й тур|{$data[0]}|{$data[2]}|";
            $this->line($string);
            $schedule .= $string . PHP_EOL;
            $i += 1;
        }
        Storage::put("schedules/group/{$this->argument('id')}.txt", $schedule);
    }
}
