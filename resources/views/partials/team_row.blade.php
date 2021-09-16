<tr data-id="{{ $teamPlayer ? $teamPlayer->player->id : '#{id}' }}">
    <td>
        @if($teamPlayer)
            @switch($teamPlayer->isCaptain)
                @case(1)<span class="badge bg-success">C</span>@break
                @case(2)<span class="badge bg-warning">A</span>@break
            @endswitch
        @endif
    </td>
    <td>
        @if($teamPlayer)
            <a href="{{ route('player', ['player' => $teamPlayer->player->id]) }}">{{ $teamPlayer->player->tag }}</a>
            <small>{{ $teamPlayer->player->name }}</small>
        @else
            <a href="{{ route('players') }}/#{id}">#{tag}</a>
            <small>#{name}</small>
        @endif
    </td>
    <td class="text-end">
        @can('update', $team)
            <div class="btn-group mr-2 captain-toggle" role="group" aria-label="First group">
                <button type="button" data-captain="1"
                        class="btn btn-primary btn-sm {{ $teamPlayer && $teamPlayer->isCaptain === 1 ? 'active' : '' }}">
                    Капитан
                </button>
                <button type="button" type="button" data-captain="2"
                        class="btn btn-primary btn-sm {{ $teamPlayer && $teamPlayer->isCaptain === 2 ? 'active' : '' }}">
                    Заместитель
                </button>
                <button type="button" type="button" data-captain="0"
                        class="btn btn-primary btn-sm {{ !$teamPlayer || $teamPlayer->isCaptain === 0 ? 'active' : '' }}">
                    Игрок
                </button>
            </div>
            <button class="btn btn-danger btn-sm delete-player"
                    data-id="{{ $teamPlayer ? $teamPlayer->player->id : '#{id}' }}">
                <i class="fas fa-times"></i>
            </button>
        @endcan
    </td>
</tr>
