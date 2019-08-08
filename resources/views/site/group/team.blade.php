@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.team', $tournamentTeam, $title) }}
    <h2>
        <i class="fab fa-{{ $tournamentTeam->team->platform->icon }}"></i>
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
                                    {{ $tournamentTeam->division === $divisionId ? 'selected' : '' }}>
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
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            TRNMNT_sendData({
                selector: '#team-edit',
                method: 'post',
                url: '{{ action('Ajax\GroupController@editTeam', ['tournamentId' => $tournamentTeam->tournament_id, 'teamId' => $tournamentTeam->team_id])}}',
                success: function (response) {
                    window.location.href = '{{ route('group.tournament', ['tournamentId' => $tournamentTeam->tournament_id]) }}';
                }
            });

            TRNMNT_deleteData({
                selector: '#team-delete-button',
                url: '{{ action('Ajax\GroupController@deleteTeam', ['tournamentId' => $tournamentTeam->tournament_id, 'teamId' => $tournamentTeam->team_id])}}',
                success: function () {
                    window.location.href = '{{ route('group.tournament', ['tournamentId' => $tournamentTeam->tournament_id]) }}';
                }
            });
        });
    </script>
@endsection