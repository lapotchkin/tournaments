@extends('layouts.site')

@section('title', 'Данные для ВК: ' . $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.copypaste', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])

    <h3>Команды</h3>
    @foreach($tournament->teams as $team)
        <div>{{ $loop->iteration }}. {{ $team->name }}</div>
    @endforeach

    <h3 class="mt-3">Группы</h3>
    @foreach($divisions as $number => $division)
        <h4>Группа {{ TextUtils::divisionLetter($number) }}</h4>
        @foreach($division as $team)
            <div>{{ $team->name }}</div>
        @endforeach
    @endforeach
@endsection