@extends('layouts.site')

@section('title', 'Командные турниры — ')

@section('content')
    {{ Breadcrumbs::render('group') }}
    <h2>
        Командные турниры
        @can('create', 'App\Models\GroupTournament')
            <a class="btn btn-primary" href="{{ route('group.new') }}">
                <i class="fas fa-plus"></i> <i class="fas fa-users"></i>
            </a>
        @endcan
    </h2>
    @foreach($apps as $app)
        @if(count($app->groupTournaments))
            <div class="my-3 p-3 bg-white rounded border border-gray">
                <h4 class="border-bottom border-gray pb-2 mb-0">{{ $app->title }}</h4>
                @foreach($app->groupTournaments as $tournament)
                    <div class="d-flex pt-2">
                        <div class="flex-shrink-0">
                            <i class="fab fa-2x fa-{{ $tournament->platform->icon }} {{ $tournament->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
                        </div>
                        <div
                            class="flex-grow-1 ms-2 pb-2 ml-2 mb-0 lh-125 {{ !$loop->last ? 'border-bottom border-gray' : '' }}">
                            @if(count($tournament->winners))
                                <div class="float-end">
                                    @foreach($tournament->winners as $winner)
                                        <span class="fa-stack" style="vertical-align: top;">
                                          <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                                        </span>
                                        <a href="{{ route('team', ['team' => $winner->team->id]) }}">{{ $winner->team->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('group.tournament', ['groupTournament' => $tournament->id]) }}">{{ $tournament->title }}</a>
                            <span class="badge rounded-pill bg-secondary">
                                {{ $tournament->min_players }} на {{ $tournament->min_players }}
                            </span>
                            <br>
                            <span class="text-muted">Создан:</span>
                            {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}
                            @if($tournament->startedAt)
                                <span class="text-muted ml-3">Начат:</span>
                                {{ (new \DateTime($tournament->startedAt))->format('d.m.Y') }}
                            @endif
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
