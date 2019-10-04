<?php

namespace App\Utils;

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

class Vk
{
    /**
     * @param string $imagePath
     * @param int    $groupId
     * @param string $message
     * @return mixed
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
     */
    public static function wallPost(string $imagePath, int $groupId, string $message)
    {
        $vk = new VKApiClient(env('VK_API_VERSION'));
        $address = $vk->photos()->getWallUploadServer(env('VK_ACCESS_TOKEN'), ['group_id' => $groupId]);
        $photo = $vk->getRequest()->upload($address['upload_url'], 'photo', $imagePath);
        $response_save_photo = $vk->photos()->saveWallPhoto(env('VK_ACCESS_TOKEN'), [
            'group_id' => $groupId,
            'server'   => $photo['server'],
            'photo'    => $photo['photo'],
            'hash'     => $photo['hash'],
        ]);
        $post = $vk->wall()->post(env('VK_ACCESS_TOKEN'), [
            'owner_id'    => intval('-' . $groupId),
            'from_group'  => 1,
            'message'     => $message,
            'attachments' => 'photo' . $response_save_photo[0]['owner_id'] . '_' . $response_save_photo[0]['id'],
        ]);

        return $post;
    }
}
