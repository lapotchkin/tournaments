@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (ВК) — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.schedule', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

    @foreach($rounds as $round => $groups)
        <h3>Тур {{ $round }}</h3>
        @foreach($groups as $group => $games)
            <h4>Группа {{ TextUtils::divisionLetter($group) }}</h4>
            @foreach($games as $game)
                <div>
                    {{ $loop->iteration }}.
                    {{ $game->homeTeam->team->name }} — {{ $game->awayTeam->team->name }}
                </div>
            @endforeach
        @endforeach
    @endforeach
@endsection