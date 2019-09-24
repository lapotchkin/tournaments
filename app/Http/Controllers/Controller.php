<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Построить AJAX-ответ
     * @param array  $data    Данные ответа
     * @param string $message Текст сообщения ответа
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
}
