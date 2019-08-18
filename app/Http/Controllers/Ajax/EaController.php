<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class EaController
 * @package App\Http\Controllers\Ajax
 */
class EaController extends Controller
{
    const BASE_URL = 'https://www.easports.com';
    const API_PATH = 'iframe/nhl14proclubs/api/platforms/{platform}/clubs/{clubId}';
    const MATCHES_PATH = 'matches';

    public function getLastGames()
    {

    }
}
