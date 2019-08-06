@extends('layouts.site')

@section('title', 'Командные турниры — ')

@section('content')
    <h2><i class="fas fa-users"></i> Командные турниры</h2>
    @foreach($platforms as $platform)
        @if(count($platform->groupTournaments))
            <h3><i class="fab fa-{{ $platform->icon }}"></i> {{ $platform->name }}</h3>

            <ul class="fa-ul">
                @foreach($platform->groupTournaments as $tournament)
                    <li>
                        <span class="fa-li"><i class="fas fa-gamepad"></i></span>
                        {{ $tournament->title }}
                        <span class="badge badge-pill badge-secondary">
                            {{ $tournament->min_players }} на {{ $tournament->min_players }}
                        </span>
                        Создан: {{ $tournament->createdAt }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
@endsection