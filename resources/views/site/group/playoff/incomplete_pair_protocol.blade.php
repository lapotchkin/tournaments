@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.playoff.game.add', $pair) }}

    <h3>{{ $title }}</h3>
    <p>Пара неполная</p>
@endsection
