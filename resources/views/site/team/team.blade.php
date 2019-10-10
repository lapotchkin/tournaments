@extends('layouts.site')

@section('title', $team->name . ' — ')

@section('content')
    {{ Breadcrumbs::render('team', $team) }}
    <h2>
        <i class="fab fa-{{ $team->platform->icon }} {{ $team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $team->name }}
        @auth
            @if(Auth::user()->isAdmin())
                <a class="btn btn-primary" href="{{ route('team.edit', ['teamId' => $team->id]) }}">
                    <i class="fas fa-edit"></i>
                </a>
            @endif
        @endauth
    </h2>

    @if(count($team->players))
        <h3>Игроки команды</h3>
        <ul class="fa-ul" id="team-players">
            @foreach($team->players as $player)
                <li>
                    <span class="fa-li"><i class="fas fa-user"></i></span>
                    <a href="{{ route('player', ['playerId' => $player->id]) }}">{{ $player->tag }}</a>
                    <small>{{ $player->name }}</small>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <button class="btn btn-danger btn-sm delete-player" data-id="{{ $player->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    @endauth
                </li>
            @endforeach
        </ul>
    @endif

    @auth
        @if(Auth::user()->isAdmin())
            <form id="player-add">
                <div class="form-inline">
                    <div class="input-group">
                        <label for="player_id" class="mr-2">Игрок</label>
                        <select id="player_id" class="form-control mr-3" name="player_id">
                            <option value="">--Не выбран--</option>
                            @foreach($nonTeamPlayers as $player)
                                <option
                                    value="{{ $player->id }}">{{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="player-add-button">Добавить</button>
                </div>
            </form>
        @endif
    @endauth

    @if(count($team->tournaments))
        <h3 class="mt-3">Командные турниры</h3>
        <ul class="fa-ul">
            @foreach($team->tournaments as $tournament)
                <li>
                    <span class="fa-li"><i class="fas fa-hockey-puck"></i></span>
                    <a href="{{ route('group.tournament', ['tournamentId' => $tournament->id]) }}">{{ $tournament->title }}</a>
                    <span class="badge badge-secondary badge-pill">
                        {{ $tournament->min_players }} на {{ $tournament->min_players }}
                    </span>
                    @foreach($tournament->winners as $winner)
                        @if($winner->team_id === $team->id)
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-trophy fa-stack-1x fa-inverse text-{{ TextUtils::winnerClass($winner->place) }}"></i>
                            </span>
                        @endif
                    @endforeach
                </li>
            @endforeach
        </ul>
    @endif
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

    @auth
        @if(Auth::user()->isAdmin())
            <script type="text/javascript">
                $(document).ready(function () {
                    TRNMNT_sendData({
                        selector: '#player-add',
                        method: 'put',
                        url: '{{ action('Ajax\TeamController@addPlayer', ['teamId' => $team->id])}}',
                        success: function (response) {
                            const $select = $('#player_id');
                            const playerId = $select.val();
                            const $option = $('#player_id option[value=' + playerId + ']');
                            const newPlayerData = getPlayerDataFromOption($option);
                            console.log(newPlayerData);
                            const $list = $('#team-players');
                            const $item = $(`
                                <li>
                                    <span class="fa-li"><i class="fas fa-user"></i></span>
                                    <a href="{{ route('players') }}/${playerId}">${newPlayerData[0]}</a>
                                    <small>${newPlayerData[1] ? newPlayerData[1] : ''}</small>
                                    <button class="btn btn-danger btn-sm delete-player" data-id="${playerId}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </li>
                            `);
                            $list.find('li').each(function (index, element) {
                                const $element = $(element);
                                const playerData = getPlayerDataFromLi($element);
                                console.log(playerData);
                                if (playerData[0].toLowerCase() > newPlayerData[0].toLowerCase()) {
                                    $element.before($item);
                                    return false;
                                }
                            });
                            $select.val('');
                            $option.remove();
                        }
                    });

                    TRNMNT_deleteData({
                        selector: '.delete-player',
                        url: '{{ action('Ajax\TeamController@addPlayer', ['teamId' => $team->id])}}',
                        success: function (response, $button) {
                            const $item = $button.parent('li');
                            const playerId = $button.data('id');
                            const removedPlayerData = getPlayerDataFromLi($item);
                            const $select = $('#player_id');
                            const option = '<option value="' + playerId + '">'
                                + removedPlayerData[0]
                                + (removedPlayerData[1] ? '(' + removedPlayerData[1] + ')' : '')
                                + '</option>';
                            const $option = $(option);
                            $select.find('option').each(function (index, element) {
                                if (index > 0) {
                                    const $element = $(element);
                                    const playerData = getPlayerDataFromOption($element);
                                    if (playerData[0].toLowerCase() > removedPlayerData[0].toLowerCase()) {
                                        $element.before($option);
                                        return false;
                                    }
                                }
                            });
                            $item.remove();
                        }
                    });
                });

                function getPlayerDataFromOption($option) {
                    const playerData = $option.text().split(' (');
                    for (let i = 0; i < playerData.length; i += 1) {
                        playerData[i] = playerData[i].trim().replace(')', '');
                    }
                    return playerData;
                }

                function getPlayerDataFromLi($item) {
                    // const playerData;
                    const html = $item.html();
                    const tag = html.match(/<a[\s\w=\":\/\-\.]+>([А-Яа-яЁё\w\s\-\_]+)<\/a>/);
                    const name = html.match(/<small>([А-Яа-яЁё\w\s]+)/);
                    return [
                        tag && tag[1] ? tag[1] : '',
                        name && name[1] ? name[1] : '',
                    ];
                }
            </script>
        @endif
    @endauth
@endsection
