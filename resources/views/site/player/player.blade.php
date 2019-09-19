@extends('layouts.site')

@section('title', $player->name .' (' . $player->tag . ') — ')

@section('content')
    {{ Breadcrumbs::render('player', $player) }}
    <h2>
        <i class="fab fa-{{ $player->platform->icon }} {{ $player->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $player->name }} <small class="text-muted">{{ $player->tag }}</small>
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('player.edit', ['playerId' => $player->id]) }}">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
        @endauth
    </h2>

    @if(count($player->teamPlayers))
        <h3>Команды игрока</h3>
        <ul class="fa-ul">
            @foreach($player->teamPlayers as $teamPlayer)
                <li>
                    <span class="fa-li"><i class="fas fa-users"></i></span>
                    <a href="{{ route('team', ['teamId' => $teamPlayer->team_id]) }}">{{ $teamPlayer->team->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    @if(count($player->personalTournamentPlayers))
        <h3 class="mt-3">Турниры 1 на 1</h3>
        <ul class="fa-ul">
            @foreach($player->personalTournamentPlayers as $personalTournamentPlayer)
                <li>
                    <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                    <a href="{{ route('personal.tournament', ['tournamentId' => $personalTournamentPlayer->tournament_id]) }}">{{ $personalTournamentPlayer->tournament->title }}</a>
                    @foreach($personalTournamentPlayer->tournament->winners as $winner)
                        @if($winner->player_id === $player->id)
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                            </span>
                        @endif
                    @endforeach
                </li>
            @endforeach
        </ul>
    @endif
@endsection

@section('script')
    @parent
    <style>
        .fa-stack {
            font-size: 0.5rem;
        }

        i {
            vertical-align: middle;
        }
    </style>
@endsection
