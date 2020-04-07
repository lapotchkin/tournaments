<?php

namespace App\Http\Controllers;

use App\Models\TeamPlayer;
use App\Utils\ScoreImage;
use App\Utils\Vk;
use Auth;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Построить AJAX-ответ
     *
     * @param array  $data    Данные ответа
     * @param string $message Текст сообщения ответа
     *
     * @return ResponseFactory|Response
     */
    protected function renderAjax(array $data = [], $message = '')
    {
        return response(
            json_encode([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    protected function sortWinners($a, $b)
    {
        if ($a->cups[1] === $b->cups[1] && $a->cups[2] === $b->cups[2] && $a->cups[3] === $b->cups[3]) {
            return 0;
        } elseif ($a->cups[1] > $b->cups[1]) {
            return -1;
        } elseif ($a->cups[1] === $b->cups[1] && $a->cups[2] > $b->cups[2]) {
            return -1;
        } elseif ($a->cups[1] === $b->cups[1] && $a->cups[2] === $b->cups[2] && $a->cups[3] > $b->cups[3]) {
            return -1;
        }
        return 1;
    }

    /**
     * @param $game
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
    protected static function postToVk($game)
    {
        if (is_null($game->tournament->vk_group_id)) {
            return;
        }

        $scoreImage = new ScoreImage($game);
        $imagePath = $scoreImage->create();
        $photo = Vk::uploadWallPhoto($imagePath, $game->tournament->vk_group_id);
        if (isset($game->homeTeam)) {
            $text = $game->homeTeam->team->name . ' против ' . $game->awayTeam->team->name;
        } else {
            $text = $game->homePlayer->name . ' (' . $game->homePlayer->tag . ') ' . mb_strtoupper($game->homePlayer->getClubId($game->tournament->id)) . PHP_EOL . 'против' . PHP_EOL . $game->awayPlayer->name . ' (' . $game->awayPlayer->tag . ') ' . mb_strtoupper($game->awayPlayer->getClubId($game->tournament->id));
        }
        Vk::wallPost($photo, $game->tournament->vk_group_id, $text);
    }
}
