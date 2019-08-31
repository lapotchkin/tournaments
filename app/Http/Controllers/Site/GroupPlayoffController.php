<?php

namespace App\Http\Controllers\Site;

use App\Models\GroupTournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupPlayoffController extends Controller
{
    public function index(Request $request, int $tournamentId)
    {
        /** @var GroupTournament $tournament */
        $tournament = GroupTournament::find($tournamentId);
        if (is_null($tournament)) {
            abort(404);
        }

        $maxTeams = pow(2, $tournament->playoff_rounds);
        $bracket = [];
        for ($i = 1; $i <= $tournament->playoff_rounds; $i += 1) {
            for ($j = 1; $j <= $maxTeams / pow(2, $i); $j += 1) {
                $bracket[$i][$j] = null;
            }
        }
        foreach ($tournament->playoff as $playoff) {
            $bracket[$playoff->round][$playoff->pair] = $playoff;
        }

        return view('site.group.playoff.index', [
            'tournament' => $tournament,
            'maxTeams'   => $maxTeams,
            'bracket'    => $bracket,
        ]);
    }
}
