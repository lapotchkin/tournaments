@extends('layouts.site')

@section('title', 'Командные турниры — ')

@section('content')
    {{ Breadcrumbs::render('group') }}
    <h2>
        Командные турниры
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('group.new') }}">
                    <i class="fas fa-plus"></i> <i class="fas fa-users"></i>
                </a>
            @endif
        @endauth
    </h2>
    @foreach($apps as $app)
        @if(count($app->groupTournaments))
            <div class="my-3 p-3 bg-white rounded border border-gray">
                <h4 class="border-bottom border-gray pb-2 mb-0">{{ $app->title }}</h4>
                @foreach($app->groupTournaments as $tournament)
                    <div class="media pt-2">
                        <i
                            class="fab fa-2x fa-{{ $tournament->platform->icon }} {{ $tournament->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
                        <div
                            class="media-body pb-2 ml-2 mb-0 lh-125 {{ !$loop->last ? 'border-bottom border-gray' : '' }}">
                            @if(count($tournament->winners))
                                <div class="float-right">
                                    @foreach($tournament->winners as $winner)
                                        <span class="fa-stack" style="vertical-align: top;">
                                          <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                                        </span>
                                        <a href="{{ route('team', ['team' => $winner->team->id]) }}">{{ $winner->team->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('group.tournament', ['tournamentId' => $tournament->id]) }}">
                                {{ $tournament->title }}
                            </a>
                            <span class="badge badge-pill badge-secondary">
                                {{ $tournament->min_players }} на {{ $tournament->min_players }}
                            </span>
                            <br>
                            Создан: {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
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
