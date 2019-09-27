<?php

namespace App\Console\Commands;

use App\Models\GroupGameRegular;
use App\Utils\ScoreImage;
use Exception;
use Illuminate\Console\Command;

class ImageGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate image';

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
        $game = GroupGameRegular::find(446);
        $scoreImage = new ScoreImage($game);
        $scoreImage->create();
    }
}
