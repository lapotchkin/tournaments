@extends('layouts.site')

@section('title', 'Трансферы — ')

@section('content')
    {{ Breadcrumbs::render('tracker') }}

    <h2>Трансферы</h2>

    <form id="player-add" class="mb-2">
        <div class="form-inline">
            <div class="input-group">
                <label for="team" class="mr-2">Команда</label>
                <select id="team" class="form-control mr-2" name="team">
                    <option value="">--Не выбрана--</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ request()->get('team') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->platform->name }})
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary mr-2">Выбрать</button>
            <a href="{{ route('tracker') }}" class="btn btn-danger">Сбросить</a>
        </div>
    </form>

    <table class="table table-sm table-striped">
        <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Команда</th>
            <th>Дата</th>
            <th>Трансфер</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transfers as $transfer)
            <tr>
                <td>
                    @switch($transfer->action_id)
                        @case(\App\Http\Controllers\Ajax\TeamController::ADD_TO_TEAM)
                        <i class="fas fa-user-plus fa-fw text-success"></i>
                        @break
                        @case(\App\Http\Controllers\Ajax\TeamController::DELETE_FROM_TEAM)
                        <i class="fas fa-user-minus fa-fw text-danger"></i>
                        @break
                        @case(\App\Http\Controllers\Ajax\TeamController::SET_AS_CAPTAIN)
                        <span class="badge badge-success">C</span>
                        @break
                        @case(\App\Http\Controllers\Ajax\TeamController::SET_AS_ASSISTANT)
                        <span class="badge badge-warning">A</span>
                        @break
                        @case(\App\Http\Controllers\Ajax\TeamController::SET_AS_PLAYER)
                        <i class="fas fa-user fa-fw text-secondary"></i>
                        @break
                    @endswitch
                </td>
                <td>
                    <i class="fab fa-{{ $transfer->team->platform->icon }} {{ $transfer->team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
                    <a href="{{ route('team', ['team' => $transfer->team_id]) }}">
                        {{ $transfer->team->name }}
                    </a>
                </td>
                <td>
                    <span>{{ $transfer->createdAt->format('d.m.y') }} в {{ $transfer->createdAt->format('H:i') }}</span>
                </td>
                <td>
                    <a href="{{ route('player', ['player' => $transfer->manager_id]) }}">{{ $transfer->manager->tag }}</a>
                    {{ $transfer->action->title }}
                    <a href="{{ route('player', ['player' => $transfer->player_id]) }}">{{ $transfer->player->tag }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if(request()->get('team'))
        {{ $transfers->appends(['team' => request()->get('team')])->links() }}
    @else
        {{ $transfers->links() }}
    @endif
@endsection
