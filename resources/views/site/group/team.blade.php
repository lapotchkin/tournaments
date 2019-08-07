@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.team', $groupTournamentTeam, $title) }}
    <h2>
        <i class="fab fa-{{ $groupTournamentTeam->team->platform->icon }}"></i>
        {{ $title }}
    </h2>

    <div class="row">
        <div class="col-lg-5">
            <form id="team-edit">
                <div class="form-group">
                    <label for="division">Группа</label>
                    <select id="division" class="form-control" name="division">
                        @foreach([1, 2, 3, 4] as $divisionId)
                            <option value="{{ $divisionId }}"
                                    {{ $groupTournamentTeam->division === $divisionId ? 'selected' : '' }}>
                                {{ TextUtils::divisionLetter($divisionId) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Применить изменения</button>
                    <button type="button" class="btn btn-danger" id="team-delete-button">Удалить</button>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            TRNMNT_sendData({
                selector: '#team-edit',
                method: 'post',
                url: '{{ action('Ajax\GroupController@editTeam', ['tournamentId' => $groupTournamentTeam->tournament_id, 'teamId' => $groupTournamentTeam->team_id])}}',
                success: function (response) {
                    window.location.href = '{{ action('Site\GroupController@teams', ['tournamentId' => $groupTournamentTeam->tournament_id]) }}';
                }
            });

            TRNMNT_deleteData({
                selector: '#team-delete-button',
                url: '{{ action('Ajax\GroupController@deleteTeam', ['tournamentId' => $groupTournamentTeam->tournament_id, 'teamId' => $groupTournamentTeam->team_id])}}',
                success: function () {
                    window.location.href = '{{ action('Site\GroupController@teams', ['tournamentId' => $groupTournamentTeam->tournament_id]) }}';
                }
            });
        });
    </script>
@endsection