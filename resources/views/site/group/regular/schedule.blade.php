@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (ВК) — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.schedule', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

    @foreach($rounds as $round => $divisions)
        <h3 class="{{ !$loop->first ? 'mt-3' : '' }}">Тур {{ $round }}</h3>
        @foreach($divisions as $division => $games)
            @if (count($divisions) > 1)
                <h4 class="{{ !$loop->first ? 'mt-2' : '' }}">Группа {{ TextUtils::divisionLetter($division) }}</h4>
            @endif
            @foreach($games as $game)
                <div>
                    {{ $loop->iteration }}.
                    {{ $game->homeTeam->team->name }} — {{ $game->awayTeam->team->name }}
                </div>
            @endforeach
        @endforeach
    @endforeach
@endsection