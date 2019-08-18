<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class GetEashlLogos
 * @package App\Console\Commands
 */
class GetEashlLogos extends Command
{
    const LOGO_PATH = 'https://www.easports.com/iframe/nhl14proclubs/bundles/nhl/dist/images/custom-crests';
    const LAST_LOGO_INDEX = 255;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:logos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get EASHL logos';

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
     */
    public function handle()
    {
        for ($i = 0; $i < self::LAST_LOGO_INDEX + 1; $i += 1) {
            try {
                $file = file_get_contents(self::LOGO_PATH . "/{$i}.png");
                file_put_contents(public_path('images/logo') . "/{$i}.png", $file);
                $this->info("Logo {$i}.png saved");
            } catch (\Exception $e) {
                $this->error("Can't get file {$i}.png: {$e->getMessage()}");
            }
        }
    }
}
