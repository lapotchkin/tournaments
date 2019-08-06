<?php

namespace App\Http\Controllers\Site;

use App\Models\Club;
use App\Models\League;
use App\Models\Platform;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class HomeController
 * @package App\Http\Controllers\Site
 */
class HomeController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $platforms = Platform::all();
        return view('site.home.index', [
            'platforms' => $platforms,
        ]);
    }
}
