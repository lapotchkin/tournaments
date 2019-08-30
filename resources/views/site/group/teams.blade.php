@extends('layouts.site')

@section('title', $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])

    <h3>Команды</h3>
    <div class="card-deck">
        @foreach($divisions as $number => $division)
            <div class="card mb-3" id="division-{{ $number }}">
                <h4 class="card-header bg-dark text-light">Группа {{ TextUtils::divisionLetter($number) }}</h4>
                <div class="card-body">
                    <table class="table table-striped table-sm" id="team-table">
                        <tbody>
                        @foreach($division as $team)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('team', ['teamId' => $team->id]) }}">{{ $team->name }}</a>
                                    <span class="badge badge-success badge-pill">{{ $team->short_name }}</span>
                                </td>
                                <td class="text-right">
                                    @auth
                                        @if(Auth::user()->isAdmin())
                                            <a class="btn btn-primary btn-sm"
                                               href="{{ route('group.tournament.team', ['tournamentId' => $tournament->id, 'teamId' => $team->id]) }}">
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
        @endforeach
    </div>

    <form id="team-add">
        <div class="form-row">
            <div class="form-group">
                <div class="form-group">
                    <label for="team_id">Команда</label>
                    <select id="team_id" class="form-control" name="team_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($nonTournamentTeams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group col-2">
                <div class="form-group">
                    <label for="division">Группа</label>
                    <select id="division" class="form-control" name="division">
                        <option value="">--Не выбрана--</option>
                        @foreach([1, 2, 3, 4] as $divisionId)
                            <option value="{{ $divisionId }}">
                                {{ TextUtils::divisionLetter($divisionId) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group ml-2" style="margin-top: 2rem">
                <button type="submit" class="btn btn-primary" name="team-add-button">Добавить</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    @parent
    @auth
        @if(Auth::user()->isAdmin())
            <script type="text/javascript">
                $(document).ready(function () {

                    TRNMNT_sendData({
                        selector: '#team-add',
                        method: 'put',
                        url: '{{ action('Ajax\GroupController@addTeam', ['tournamentId' => $tournament->id])}}',
                        success: function (response) {
                            var $division = $('#division');
                            var division = $division.val();
                            createDivisionBlock(division);
                            var $team = $('#team_id');
                            var teamId = $team.val();
                            var $option = $('#team_id option[value=' + teamId + ']');
                            var $tbody = $('#division-' + division).find('tbody');
                            var $row = $('<tr/>');
                            $row.append('<td>' + ($tbody.find('tr').length + 1) + '</td>');
                            $row.append('<td><a href="{{ action('Site\TeamController@index') }}/' + teamId + '">' + $option.text() + '</a></td>');
                            $row.append('<td class="text-right"><a class="btn btn-primary btn-sm" href="{{ route('group.tournament', ['tournamentId' => $tournament->id]) }}/team/' + teamId + '"><i class="fas fa-edit"></i></a></td>');
                            $tbody.append($row);
                            $option.remove();
                            $team.val('');
                            $division.val('');
                        }
                    });

                    function createDivisionBlock(division) {
                        if ($('#division-' + division).length) return;

                        var letters = 'ABCD';
                        $('.card-deck').append(
                            '<div class="card mb-3" id="division-' + division + '"><h4 class="card-header">Группа ' + letters.charAt(division - 1) + '</h4><div class="card-body"><table class="table table-striped table-sm" id="team-table"><tbody></tbody></table></div></div>');
                    }
                });
            </script>
        @endif
    @endauth
@endsection
