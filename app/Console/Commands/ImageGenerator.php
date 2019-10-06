<?php

namespace App\Console\Commands;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Utils\ScoreImage;
use App\Utils\Vk;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiParamAlbumIdException;
use VK\Exceptions\Api\VKApiParamHashException;
use VK\Exceptions\Api\VKApiParamServerException;
use VK\Exceptions\Api\VKApiWallAddPostException;
use VK\Exceptions\Api\VKApiWallAdsPostLimitReachedException;
use VK\Exceptions\Api\VKApiWallAdsPublishedException;
use VK\Exceptions\Api\VKApiWallLinksForbiddenException;
use VK\Exceptions\Api\VKApiWallTooManyRecipientsException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class ImageGenerator extends Command
{
    const PHOTOS_PER_POST = 10;
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
        /** @var GroupTournament[] $groupTournaments */
        $groupTournaments = GroupTournament::all();
        foreach ($groupTournaments as $tournament) {
            $this->_processTournament($tournament);
        }
        /** @var PersonalTournament[] $personalTournaments */
        $personalTournaments = GroupTournament::all();
        foreach ($personalTournaments as $tournament) {
            $this->_processTournament($tournament);
        }
    }

    /**
     * @param GroupTournament|PersonalTournament $tournament
     * @throws Exception
     */
    private function _processTournament($tournament)
    {
        $this->info($tournament->title);
        if (count($tournament->winners)) {
            $this->line('Tournament finished');
            return;
        }

        if (is_null($tournament->vk_group_id)) {
            $this->line('Tournament without group');
            return;
        }

        $regularGames = $tournament->getNotSharedRegularGames();
        $this->_shareGames($regularGames, $tournament);
        $playoffGames = $tournament->getNotSharedPlayoffGames();
        $this->_shareGames($playoffGames, $tournament);
    }

    /**
     * @param $games
     * @param $tournament
     * @throws VKApiParamAlbumIdException
     * @throws VKApiParamHashException
     * @throws VKApiParamServerException
     * @throws VKApiWallAddPostException
     * @throws VKApiWallAdsPostLimitReachedException
     * @throws VKApiWallAdsPublishedException
     * @throws VKApiWallLinksForbiddenException
     * @throws VKApiWallTooManyRecipientsException
     * @throws VKApiException
     * @throws VKClientException
     * @throws Exception
     */
    private function _shareGames($games, $tournament)
    {
        $pucks = [];
        $i = -1;
        foreach ($games as $index => $game) {
            if ($index % self::PHOTOS_PER_POST === 0) {
                $i += 1;
            }
            $pucks[$i][] = $game;
        }

        if (empty($pucks)) {
            $this->line('No games to post');
            return;
        }

        foreach ($pucks as $index => $puck) {
            $this->line('Puck ' . ($index + 1));
            $photos = [];
            $text = '';
            foreach ($puck as $game) {
                $this->line($game->id);
                $scoreImage = new ScoreImage($game);
                $imagePath = $scoreImage->create();
                $photos[] = Vk::uploadWallPhoto($imagePath, $tournament->vk_group_id);
                if (isset($game->homeTeam)) {
                    $text .= $game->homeTeam->team->name . ' ' . $game->home_score . ':' . $game->away_score . ' ' . $game->awayTeam->team->name . PHP_EOL;
                } else {
                    $text .= $game->homePlayer->name . ' (' . $game->homePlayer->tag . ') ' . mb_strtoupper($game->homePlayer->getClubId($game->tournament->id)) . ' ' . $game->home_score . ':' . $game->away_score . ' ' . $game->awayPlayer->name . ' (' . $game->awayPlayer->tag . ') ' . mb_strtoupper($game->awayPlayer->getClubId($game->tournament->id)) . PHP_EOL;
                }
                $game->sharedAt = date('Y-m-d H:i:s');
                $game->save();
            }
            Vk::wallPost(implode(',', $photos), $tournament->vk_group_id, $text);
        }
    }
}
