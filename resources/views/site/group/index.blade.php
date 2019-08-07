@extends('layouts.site')

@section('title', 'Командные турниры — ')

@section('content')
    {{ Breadcrumbs::render('group') }}
    <h2>
        Командные турниры
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ action('Site\GroupController@new') }}">
                    <i class="fas fa-plus"></i> <i class="fas fa-users"></i>
                </a>
            @endif
        @endauth
    </h2>
    @foreach($apps as $app)
        @if(count($app->groupTournaments))
            <h3>{{ $app->title }}</h3>

            <ul class="fa-ul">
                @foreach($app->groupTournaments as $tournament)
                    <li>
                        <span class="fa-li"><i
                                    class="fab fa-{{ $tournament->platform->icon }} {{ $tournament->platform->icon === 'xbox' ? 'text-success' : '' }}"></i></span>
                        <a href="{{ action('Site\GroupController@teams', ['tournamentId' => $tournament->id]) }}">
                            {{ $tournament->title }}
                        </a>
                        <span class="badge badge-pill badge-secondary">
                            {{ $tournament->min_players }} на {{ $tournament->min_players }}
                        </span>
                        Создан: {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
@endsection