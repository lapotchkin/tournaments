@extends('layouts.site')

@section('title', 'Данные для ВК: ' . $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.copypaste', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])

    <h3 class="mt-3">Игроки</h3>
    <div class="row">
        @foreach ($divisions as $division => $players)
            <div class="col">
                <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
                @foreach ($players as $index => $player)
                    <div>
                        {{ $index + 1 }}. {{ $player->player->tag }}
                        &#64;{{ $player->player->vk }}
                        @if ($player->club && $player->club->title)
                            ({{ $player->club->title }})
                        @endif
                        {{ $player->player->name }}
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <h3 class="mt-3">Группы</h3>
    <div class="row">
        @foreach ($divisions as $division => $players)
            <div class="col-2">
                <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
                @foreach ($players as $index => $player)
                    <div>{{ $player->player->tag }}</div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection