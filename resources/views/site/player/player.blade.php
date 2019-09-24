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

    @if(count($player->teams))
        <h3>Команды игрока</h3>
        <ul class="fa-ul">
            @foreach($player->teams as $team)
                <li>
                    <span class="fa-li"><i class="fas fa-users"></i></span>
                    <a href="{{ route('team', ['teamId' => $team->id]) }}">{{ $team->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    @if(count($player->tournaments))
        <h3 class="mt-3">Турниры 1 на 1</h3>
        <ul class="fa-ul">
            @foreach($player->tournaments as $tournament)
                <li>
                    <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                    <a href="{{ route('personal.tournament', ['tournamentId' => $tournament->id]) }}">{{ $tournament->title }}</a>
                    @foreach($tournament->winners as $winner)
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
