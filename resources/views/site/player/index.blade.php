@extends('layouts.site')

@section('title', 'Игроки — ')

@section('content')
    {{ Breadcrumbs::render('players') }}
    <h2>
        Игроки
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('player.add') }}">
                    <i class="fas fa-user-plus"></i>
                </a>
            @endif
        @endauth
    </h2>

    <h3>Зал славы</h3>
    @foreach($winners as $winner)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2 float-start my-3"
             role="alert">
            <div class="text-center bg-light rounded shadow-sm p-3">
                <div class="fa-2x" style="margin-top: -2.3rem;">
                    @foreach($winner->cups as $place => $count)
                        @if($count > 0)
                            <span class="fa-layers fa-fw">
                                <i class="fas fa-trophy text-{{ TextUtils::winnerClass($place) }}"></i>
                                <span class="fa-layers-text fa-inverse {{ $place === 1 ? 'text-dark' : '' }}"
                                      data-fa-transform="shrink-8 up-2" style="font-weight:900">
                                    {{ $count }}
                                </span>
                            </span>
                        @endif
                    @endforeach
                </div>
                <div>
                    <a href="{{ route('player', ['player' => $winner->player->id]) }}">{{ $winner->player->tag }}</a>
                </div>
                <div class="small">{{ $winner->player->name }}</div>
            </div>
        </div>
    @endforeach
    <div class="clearfix"></div>

    @foreach($players as $data)
        <h3 class="mt-3">
            <i class="fab fa-{{ $data->platform->icon }} {{ $data->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
            {{ $data->platform->name }}
        </h3>
        @php
            $playersInColumnCount = ceil(count($data->players) / 2);
        @endphp
        {{--@formatter:off--}}
        <div class="row">
            @foreach($data->players as $player)
                @if($loop->index % $playersInColumnCount === 0 )
                    @if($loop->index % $playersInColumnCount === 0 && !$loop->first)
                        </div>
                    @endif
                    <div class="col-6">
                @endif
                <div>
                    <i class="fas fa-user"></i>
                    <a href="{{ route('player', ['player' => $player->id]) }}">{{ $player->tag }}</a>
                    <small>{{ $player->name }}</small>
                </div>
                @if($loop->last)
                    </div>
                @endif
            @endforeach
        </div>
        {{--@formatter:on--}}
    @endforeach
@endsection
