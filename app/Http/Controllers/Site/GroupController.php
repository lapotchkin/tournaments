<?php

namespace App\Http\Controllers\Site;

use App\Models\Platform;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function index()
    {
        $platforms = Platform::all();
        return view('site.group.index', [
            'platforms' => $platforms,
        ]);
    }
}
