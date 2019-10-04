@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (ВК) — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.regular.schedule', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])
    @widget('personalRegularMenu', ['tournament' => $tournament])

    @foreach($rounds as $round => $divisions)
        <h3 class="{{ !$loop->first ? 'mt-3' : '' }}">Тур {{ $round }}</h3>
        @foreach($divisions as $division => $games)
            @if (count($divisions) > 1)
                <h4 class="{{ !$loop->first ? 'mt-2' : '' }}">Группа {{ TextUtils::divisionLetter($division) }}</h4>
            @endif
            @foreach($games as $game)
                <div>
                    {{ $loop->iteration }}.
                    <span class="text-uppercase">{{ $game->homePlayer->getClubId($game->tournament_id) }}</span>
                    [{{ trim($game->homePlayer->vk) }}|{{ trim($game->homePlayer->name) }}]
                    {{ $game->homePlayer->tag }}
                    —
                    <span class="text-uppercase">{{ $game->awayPlayer->getClubId($game->tournament_id) }}</span>
                    [{{ trim($game->awayPlayer->vk) }}|{{ trim($game->awayPlayer->name) }}]
                    {{ $game->awayPlayer->tag }}
                </div>
            @endforeach
        @endforeach
    @endforeach
@endsection
