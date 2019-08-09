@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (Расписание) — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.games', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

@endsection