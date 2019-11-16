<?php

namespace App\Http\Controllers\Site;

use App\Models\Team;
use App\Models\TeamManagement;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TrackerController extends Controller
{
    const PER_PAGE = 50;

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $transfers = TeamManagement::with('team', 'manager', 'player', 'action')
            ->orderBy('createdAt', 'desc');
        if ($request->get('team')) {
            $transfers->whereTeamId($request->get('team'));
        }
        $teams = Team::with(['platform'])
            ->orderBy('name')
            ->get();


        return view('site.tracker.index', [
            'transfers' => $transfers->paginate(self::PER_PAGE),
            'teams'     => $teams,
        ]);
    }
}
