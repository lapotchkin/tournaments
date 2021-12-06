@php
    /** @var \App\Models\GroupTournament $tournament */
    /** @var \App\Models\Team[] $division */
    /** @var \App\Models\Team[] $nonTournamentTeams */
@endphp

@extends('layouts.site')

@section('title', $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])

    <h3>Команды</h3>
    <div class="row row-cols-1 @if(count($divisions) > 1) row-cols-md-2 @endif g-4" id="card-deck">
        @foreach($divisions as $number => $division)
            <div class="col">
                <div class="card h-100 mb-3" id="division-{{ $number }}">
                    <h4 class="card-header bg-dark text-light">Группа {{ TextUtils::divisionLetter($number) }}</h4>
                    <div class="card-body">
                        <table class="table table-striped table-sm mb-0" id="team-table">
                            <tbody>
                            @foreach($division as $team)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('team', ['team' => $team->id]) }}">{{ $team->name }}</a>
                                        <span class="badge bg-success">{{ $team->short_name }}</span>
                                    </td>
                                    <td class="text-end">
                                        @auth
                                            @if(Auth::user()->isAdmin())
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{ route('group.tournament.team', ['groupTournament' => $tournament->id, 'team' => $team->id]) }}">
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
            </div>
        @endforeach
    </div>

    @can('create', 'App\Models\GroupTournament')
        <form id="team-add" class="row row-cols-lg-auto g-2 align-items-center mt-3">
            <div class="col-12">
                <div class="input-group has-validation">
                    <label for="team_id" class="input-group-text">Команда</label>
                    <select id="team_id" class="form-select" name="team_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($nonTournamentTeams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-12">
                <div class="input-group has-validation">
                    <label for="division" class="input-group-text">Группа</label>
                    <select id="division" class="form-select" name="division">
                        <option value="">--Не выбрана--</option>
                        @foreach([1, 2, 3, 4] as $divisionId)
                            <option value="{{ $divisionId }}">
                                {{ TextUtils::divisionLetter($divisionId) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary" name="team-add-button">Добавить</button>
            </div>
        </form>
    @endcan
@endsection

@section('script')
    @parent
    @can('create', 'App\Models\GroupTournament')
        <script type="text/javascript">
            $(document).ready(function () {

                TRNMNT_sendData({
                    selector: '#team-add',
                    method: 'put',
                    url: '{{ action('Ajax\GroupController@addTeam', ['groupTournament' => $tournament->id])}}',
                    success: function () {
                        const $division = $('#division');
                        const division = $division.val();
                        createDivisionBlock(division);
                        const $team = $('#team_id');
                        const teamId = $team.val();
                        const $option = $('#team_id option[value=' + teamId + ']');
                        const $tbody = $('#division-' + division).find('tbody');
                        const $row = $('<tr/>');
                        $row.append('<td>' + ($tbody.find('tr').length + 1) + '</td>');
                        //@formatter:off
                        $row.append(
                            '<td>' +
                                '<a href="{{ action('Site\TeamController@index') }}/' + teamId + '">' +
                                    $option.text() +
                                '</a>' +
                            '</td>'
                        );
                        $row.append(
                            '<td class="text-end">' +
                                '<a class="btn btn-primary btn-sm" href="{{ route('group.tournament', ['groupTournament' => $tournament->id]) }}/team/' + teamId + '">' +
                                    '<i class="fas fa-edit"></i>' +
                                '</a>' +
                            '</td>'
                        );
                        //@formatter:on
                        $tbody.append($row);
                        $option.remove();
                        $team.val('');
                        $division.val('');
                    }
                });

                function createDivisionBlock(division) {
                    if ($('#division-' + division).length) {
                        return;
                    }

                    const letters = 'ABCD';
                    //@formatter:off
                    $('#card-deck').append(
                        '<div class="col">' +
                            '<div class="card h-100 mb-3" id="division-' + division + '">' +
                                '<h4 class="card-header bg-dark text-light">' +
                                    'Группа ' + letters.charAt(division - 1) +
                                '</h4>' +
                                '<div class="card-body">' +
                                    '<table class="table table-striped table-sm" id="team-table">' +
                                        '<tbody></tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '</div>'
                    );
                    //@formatter:on
                }
            });
        </script>
    @endcan
@endsection
