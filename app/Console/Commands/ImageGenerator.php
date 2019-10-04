<?php

namespace App\Console\Commands;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Utils\ScoreImage;
use App\Utils\Vk;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

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
        //$game = GroupGameRegular::find(446);
        //$game = GroupGamePlayoff::find(78);
        //$game = PersonalGameRegular::find(350);
        $game = PersonalGamePlayoff::find(75);
        if (is_null($game->tournament->vk_group_id)) {
            $this->info('Tournament without group');
        }

        $scoreImage = new ScoreImage($game);
        $imagePath = $scoreImage->create();
        if (isset($game->homeTeam)) {
            $text = $game->homeTeam->team->name . ' против ' . $game->awayTeam->team->name;
        } else {
            $text = $game->homePlayer->name . ' (' . $game->homePlayer->tag . ') ' . mb_strtoupper($game->homePlayer->getClubId($game->tournament->id)) . PHP_EOL . 'против' . PHP_EOL . $game->awayPlayer->name . ' (' . $game->awayPlayer->tag . ') ' . mb_strtoupper($game->awayPlayer->getClubId($game->tournament->id));
        }
        Vk::wallPost($imagePath, $game->tournament->vk_group_id, $text);
    }
}
