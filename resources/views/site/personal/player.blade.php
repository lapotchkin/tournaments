@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    {{ Breadcrumbs::render('personal.tournament.player', $tournamentPlayer, $title) }}
    <h2>
        <i class="fab fa-{{ $tournamentPlayer->player->platform->icon }} {{ $tournamentPlayer->player->platform->icon === 'xbox' ? 'text-success' : '' }}"></i>
        {{ $tournamentPlayer->player->tag }}
        @if($tournamentPlayer->player->name)
            <small class="text-muted">{{ $tournamentPlayer->player->name }}</small>
        @endif
    </h2>

    <div class="row">
        <div class="col-lg-5">
            <form id="player-edit">
                <div class="form-group">
                    <label for="club_id">Клуб</label>
                    <select id="club_id" class="form-control" name="club_id">
                        <option value="">-- Не выбран --</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}"
                                {{ $club->id === $tournamentPlayer->club_id ? 'selected' : '' }}>
                                {{ $club->title }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="division">Группа</label>
                    <select id="division" class="form-control" name="division">
                        @foreach([1, 2, 3, 4] as $divisionId)
                            <option value="{{ $divisionId }}"
                                {{ $tournamentPlayer->division === $divisionId ? 'selected' : '' }}>
                                {{ TextUtils::divisionLetter($divisionId) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Применить изменения</button>
                    <button type="button" class="btn btn-danger" id="player-delete-button">Удалить</button>
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
                selector: '#player-edit',
                method: 'post',
                url: '{{ action('Ajax\PersonalController@editPlayer', ['personalTournament' => $tournamentPlayer->tournament, 'player' => $tournamentPlayer->player])}}',
                success: function (response) {
                    window.location.href = '{{ route('personal.tournament', ['personalTournament' => $tournamentPlayer->tournament]) }}';
                }
            });

            TRNMNT_deleteData({
                selector: '#player-delete-button',
                url: '{{ action('Ajax\PersonalController@deletePlayer', ['personalTournament' => $tournamentPlayer->tournament, 'player' => $tournamentPlayer->player])}}',
                success: function () {
                    window.location.href = '{{ route('personal.tournament', ['personalTournament' => $tournamentPlayer->tournament]) }}';
                }
            });
        });
    </script>
@endsection
