@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.team', $tournamentTeam, $title) }}
    <h2>
        <i class="fab fa-{{ $tournamentTeam->team->platform->icon }} {{ $tournamentTeam->team->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $title }}
    </h2>

    <div class="row">
        <div class="col-lg-5">
            <form id="team-edit">
                <div class="mb-3">
                    <label for="division" class="form-label">Группа</label>
                    <select id="division" class="form-select" name="division">
                        @foreach([1, 2, 3, 4] as $divisionId)
                            <option value="{{ $divisionId }}"
                                    {{ $tournamentTeam->division === $divisionId ? 'selected' : '' }}>
                                {{ TextUtils::divisionLetter($divisionId) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
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
                url: '{{ action('Ajax\GroupController@editTeam', ['groupTournament' => $tournamentTeam->tournament_id, 'team' => $tournamentTeam->team_id])}}',
                success: function (response) {
                    window.location.href = '{{ route('group.tournament', ['groupTournament' => $tournamentTeam->tournament_id]) }}';
                }
            });

            TRNMNT_deleteData({
                selector: '#team-delete-button',
                url: '{{ action('Ajax\GroupController@deleteTeam', ['groupTournament' => $tournamentTeam->tournament_id, 'team' => $tournamentTeam->team_id])}}',
                success: function () {
                    window.location.href = '{{ route('group.tournament', ['groupTournament' => $tournamentTeam->tournament_id]) }}';
                }
            });
        });
    </script>
@endsection
