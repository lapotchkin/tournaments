@php
    /** @var \App\Models\GroupTournament $tournament */
    /** @var \App\Models\Team[] $division */
@endphp

@extends('layouts.site')

@section('title', 'Данные для ВК: ' . $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.copypaste', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])

    @if(count($tournament->teams) < 4)
        <div class="alert alert-danger">Недостаточно команд в турнире. Должно быть хотя бы 4.</div>
    @else
        <h3>Команды</h3>
        @foreach($tournament->teams as $team)
            <div>{{ $loop->iteration }}. {{ $team->name }}</div>
        @endforeach

        @if(!count($divisions))
            <h3 class="mt-3">Группы</h3>
            @foreach($divisions as $number => $division)
                <h4>Группа {{ TextUtils::divisionLetter($number) }}</h4>
                @foreach($division as $team)
                    <div>{{ $team->name }}</div>
                @endforeach
            @endforeach
        @endif
    @endif
@endsection
