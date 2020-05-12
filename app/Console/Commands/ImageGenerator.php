<?php

namespace App\Console\Commands;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Utils\GameScoreImage;
use App\Utils\GamesScoreImage;
use App\Utils\Vk;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

/**
 * Class ImageGenerator
 *
 * @package App\Console\Commands
 */
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
     *
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
        $personalTournaments = PersonalTournament::all();
        foreach ($personalTournaments as $tournament) {
            $this->_processTournament($tournament);
        }
    }

    /**
     * @param GroupTournament|PersonalTournament $tournament
     *
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

        $this->warn('Regular');
        $regularGames = $tournament->getNotSharedRegularGames();
//        $this->_shareGamesOnSeparateImages($regularGames, $tournament);
        $this->_shareGamesOnSingleImage($regularGames, $tournament);

        $this->warn('Playoff');
        $playoffGames = $tournament->getNotSharedPlayoffGames();
//        $this->_shareGamesOnSeparateImages($playoffGames, $tournament);
        $this->_shareGamesOnSingleImage($playoffGames, $tournament, true);
    }

    /**
     * @param GroupGameRegular[]|GroupGamePlayoff[]|PersonalGameRegular[]|PersonalGamePlayoff[]|Collection $games
     * @param GroupTournament|PersonalTournament                                                           $tournament
     * @param bool                                                                                         $isPlayoff
     *
     * @throws VKApiException
     * @throws VKApiParamAlbumIdException
     * @throws VKApiParamHashException
     * @throws VKApiParamServerException
     * @throws VKApiWallAddPostException
     * @throws VKApiWallAdsPostLimitReachedException
     * @throws VKApiWallAdsPublishedException
     * @throws VKApiWallLinksForbiddenException
     * @throws VKApiWallTooManyRecipientsException
     * @throws VKClientException
     */
    private function _shareGamesOnSingleImage($games, $tournament, bool $isPlayoff = false)
    {
        $chunks = $games->chunk(self::PHOTOS_PER_POST);
        if (empty($chunks->toArray())) {
            $this->line('No games to post');
            return;
        }

        $photos = [];
        foreach ($chunks as $index => $chunk) {
            $this->line('Chunk ' . ($index + 1));
            sleep(1);
            $scoreImage = new GamesScoreImage($chunk, $tournament, $isPlayoff);
            $imagePath = $scoreImage->create();
            $photos[] = Vk::uploadWallPhoto($imagePath, $tournament->vk_group_id);
            foreach ($chunk as $game) {
                $game->sharedAt = date('Y-m-d H:i:s');
                $game->save();
            }
        }
        Vk::wallPost(implode(',', $photos), $tournament->vk_group_id, '');
    }

    /**
     * @param $games
     * @param $tournament
     *
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
    private function _shareGamesOnSeparateImages($games, $tournament)
    {
        $pucks = array_chunk($games, self::PHOTOS_PER_POST);
        if (empty($pucks)) {
            $this->line('No games to post');
            return;
        }

        foreach ($pucks as $index => $puck) {
            $this->line('Pack ' . ($index + 1));
            $photos = [];
            $text = '';
            foreach ($puck as $game) {
                sleep(1);
                $this->line($game->id);
                $scoreImage = new GameScoreImage($game);
                $imagePath = $scoreImage->create();
                $photos[] = Vk::uploadWallPhoto($imagePath, $tournament->vk_group_id);
                if (isset($game->homeTeam)) {
                    $text .= $game->homeTeam->team->name . ' ' . $game->home_score . ':' . $game->away_score . ' ' . $game->awayTeam->team->name . PHP_EOL;
                } else {
                    $text .= $game->homePlayer->name . ' ' . mb_strtoupper($game->homePlayer->getClubId($game->tournament->id)) . ' ' . $game->home_score . ':' . $game->away_score . ' ' . $game->awayPlayer->name . ' ' . mb_strtoupper($game->awayPlayer->getClubId($game->tournament->id)) . PHP_EOL;
                }
                $game->sharedAt = date('Y-m-d H:i:s');
                $game->save();
            }
            Vk::wallPost(implode(',', $photos), $tournament->vk_group_id, $text);
        }
    }
}
