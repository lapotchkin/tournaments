@extends('layouts.site')

@section('title', 'Турниры 1 на 1 — ')

@section('content')
    {{ Breadcrumbs::render('personal') }}
    <h2>
        Турниры 1 на 1
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('personal.new') }}">
                    <i class="fas fa-plus"></i> <i class="fas fa-users"></i>
                </a>
            @endif
        @endauth
    </h2>
    @foreach($apps as $app)
        @if(count($app->personalTournaments))
            <h3>{{ $app->title }}</h3>

            <ul class="fa-ul">
                @foreach($app->personalTournaments as $tournament)
                    <li>
                        <span class="fa-li"><i
                                class="fab fa-{{ $tournament->platform->icon }} {{ $tournament->platform->icon === 'xbox' ? 'text-success' : '' }}"></i></span>
                        <a href="{{ route('personal.tournament', ['tournamentId' => $tournament->id]) }}">
                            {{ $tournament->title }}
                        </a>
                        <span class="badge badge-pill badge-secondary text-uppercase">
                            {{ $tournament->league_id }}
                        </span>
                        Создан: {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
@endsection
