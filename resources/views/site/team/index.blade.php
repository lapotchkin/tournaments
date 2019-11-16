@extends('layouts.site')

@section('title', 'Команды — ')

@section('content')
    {{ Breadcrumbs::render('teams') }}
    <h2>
        Команды
        @can('create', 'App\Models\Team')
            <a class="btn btn-primary" href="{{ route('team.add') }}">
                <i class="fas fa-users"></i> <i class="fas fa-plus"></i>
            </a>
        @endcan
    </h2>

    <h3>Зал славы</h3>
    @foreach($winners as $winner)
        <div class="col-6 col-md-4 col-lg-3 float-left my-3"
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
                    <i class="fab fa-{{ $winner->team->platform->icon }} {{ $winner->team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
                    <a href="{{ route('team', ['team' => $winner->team->id]) }}">{{ $winner->team->name }}</a>
                </div>
            </div>
        </div>
    @endforeach
    <div class="clearfix"></div>

    @foreach($teams as $data)
        <h3 class="mt-3">
            <i class="fab fa-{{ $data->platform->icon }} {{ $data->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
            {{ $data->platform->name }}
        </h3>
        @php
            $teamsInColumnCount = ceil(count($data->teams) / 2);
        @endphp
        {{--@formatter:off--}}
        <div class="row">
            @foreach($data->teams as $team)
                @if($loop->index % $teamsInColumnCount === 0 )
                    @if($loop->index % $teamsInColumnCount === 0 && !$loop->first)
                        </div>
                    @endif
                    <div class="col-6">
                @endif
                <div>
                    <i class="fas fa-users"></i>
                    <a href="{{ route('team', ['team' => $team->id]) }}">{{ $team->name }}</a>
                </div>
                @if($loop->last)
                    </div>
                @endif
            @endforeach
        </div>
        {{--@formatter:on--}}
    @endforeach
@endsection
