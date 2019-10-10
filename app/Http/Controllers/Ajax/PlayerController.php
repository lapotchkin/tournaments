<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\StoreRequest;
use App\Models\Player;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Class PlayerController
 * @package App\Http\Controllers\Ajax
 */
class PlayerController extends Controller
{
    const USER_RULES = [
        'tag'         => 'required|string',
        'name'        => 'string',
        'vk'          => 'string',
        'city'        => 'string',
        'lat'         => 'numeric',
        'lon'         => 'numeric',
        'platform_id' => 'required|string|exists:platform,id',
    ];

    /**
     * @param StoreRequest $request
     * @return ResponseFactory|Response
     */
    public function create(StoreRequest $request)
    {
        $validatedData = $request->validate(self::USER_RULES);
        /** @var Player|null $player */
        $player = Player::withTrashed()
            ->whereTag($validatedData['tag'])
            ->wherePlatformId($validatedData['platform_id'])
            ->first();
        if (!is_null($player)) {
            //Восстановить пользователя, если его удалили
            if ($player->deletedAt) {
                $player->restore();
                $player->fill($validatedData);
                $player->save();

                return $this->renderAjax(['id' => $player->id]);
            }

            abort(409, 'Такой игрок уже существует');
        }

        $player = new Player;
        $player->fill($validatedData);
        $player->save();

        return $this->renderAjax(['id' => $player->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $playerId
     * @return ResponseFactory|Response
     */
    public function edit(StoreRequest $request, int $playerId)
    {
        $validatedData = $request->validate(self::USER_RULES);
        /** @var Player|null $player */
        $player = Player::find($playerId);
        if (is_null($player)) {
            abort(404, 'Игрок не найден');
        }

        if (!isset($validatedData['name'])) {
            $validatedData['name'] = '';
        }
        if (!isset($validatedData['vk'])) {
            $validatedData['vk'] = null;
        }
        if (!isset($validatedData['city'])) {
            $validatedData['city'] = null;
        }
        if (!isset($validatedData['lat'])) {
            $validatedData['lat'] = null;
        }
        if (!isset($validatedData['lon'])) {
            $validatedData['lon'] = null;
        }

        $player->fill($validatedData);
        $player->save();

        return $this->renderAjax(['id' => $player->id]);
    }

    /**
     * @param StoreRequest $request
     * @param int          $playerId
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(StoreRequest $request, int $playerId)
    {
        /** @var Player|null $player */
        $player = Player::find($playerId);
        if (is_null($player)) {
            abort(404, 'Игрок не найден');
        }

        $player->delete();

        return $this->renderAjax();
    }
}
