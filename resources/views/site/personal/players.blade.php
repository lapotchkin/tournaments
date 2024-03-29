@extends('layouts.site')

@section('title', $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament', $tournament) }}
    @widget('personalHeader', ['tournament' => $tournament])
    @widget('personalMenu', ['tournament' => $tournament])

    <h3>Игроки</h3>
    <div class="card-deck">
        @foreach($divisions as $number => $division)
            <div class="card mb-3" id="division-{{ $number }}">
                <h4 class="card-header bg-dark text-light">Группа {{ TextUtils::divisionLetter($number) }}</h4>
                <div class="card-body">
                    <table class="table table-striped table-sm mb-0" id="player-table">
                        <tbody>
                        @foreach($division as $player)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('player', ['player' => $player->player_id]) }}">{{ $player->player->tag }}</a>
                                    <small>{{ $player->player->name }}</small>
                                    <span
                                        class="badge bg-secondary rounded-pill text-uppercase">{{ $player->club_id }}</span>
                                </td>
                                <td class="text-end">
                                    @auth
                                        @if(Auth::user()->isAdmin())
                                            <a class="btn btn-primary btn-sm"
                                               href="{{ route('personal.tournament.player', ['personalTournament' => $tournament, 'player' => $player->player]) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($loop->iteration % 2 === 0)
                <div class="w-100"></div>
            @endif
        @endforeach
    </div>

    @auth
        @if(Auth::user()->isAdmin())
            <form id="player-add">
                <div class="form-inline">
                    <div class="input-group">
                        <label for="player_id" class="mr-2">Игрок</label>
                        <select id="player_id" class="form-select mr-3" name="player_id">
                            <option value="">--Не выбран--</option>
                            @foreach($nonTournamentPlayers as $player)
                                <option
                                    value="{{ $player->id }}">{{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group">
                        <label for="division" class="mr-2">Группа</label>
                        <select id="division" class="form-select mr-3" name="division">
                            <option value="">--Не выбрана--</option>
                            @foreach([1, 2, 3, 4] as $divisionId)
                                <option value="{{ $divisionId }}">
                                    {{ TextUtils::divisionLetter($divisionId) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="player-add-button">Добавить</button>
                </div>
            </form>
        @endif
    @endauth
@endsection

@section('script')
    @parent
    @auth
        @if(Auth::user()->isAdmin())
            <script type="text/javascript">
                $(document).ready(function () {

                    TRNMNT_sendData({
                        selector: '#player-add',
                        method: 'put',
                        url: '{{ action('Ajax\PersonalController@addPlayer', ['personalTournament' => $tournament->id])}}',
                        success: function (response) {
                            const $division = $('#division');
                            const division = $division.val();
                            createDivisionBlock(division);
                            const $player = $('#player_id');
                            const playerId = $player.val();
                            const $option = $('#player_id option[value=' + playerId + ']');
                            const playerData = $option.text().split(' (');
                            const $tbody = $('#division-' + division).find('tbody');
                            const $row = $('<tr/>');
                            $row.append('<td>' + ($tbody.find('tr').length + 1) + '</td>');
                            $row.append(
                                '<td>' +
                                '<a href="{{ action('Site\PlayerController@index') }}/' + playerId + '">'
                                + playerData[0].trim()
                                + '</a> '
                                + (playerData[1] ? '<small>' + playerData[1].replace(')', '') + '</small>' : '')
                                + '</td>'
                            );
                            $row.append('<td class="text-end"><a class="btn btn-primary btn-sm" href="{{ route('personal.tournament', ['personalTournament' => $tournament]) }}/player/' + playerId + '"><i class="fas fa-edit"></i></a></td>');
                            $tbody.append($row);
                            $option.remove();
                            $player.val('');
                            $division.val('');
                        }
                    });

                    function createDivisionBlock(division) {
                        if ($('#division-' + division).length) return;

                        const letters = 'ABCD';
                        $('.card-deck').append(
                            '<div class="card mb-3" id="division-' + division + '"><h4 class="card-header bg-dark text-light">Группа ' + letters.charAt(division - 1) + '</h4><div class="card-body"><table class="table table-striped table-sm" id="player-table"><tbody></tbody></table></div></div>');
                    }
                });
            </script>
        @endif
    @endauth
@endsection
