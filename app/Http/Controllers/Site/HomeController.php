<?php

namespace App\Http\Controllers\Site;

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
        return view('site.home.index');
    }
}
