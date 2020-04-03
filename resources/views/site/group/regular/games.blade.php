@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (Расписание) — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.games', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

    <div class="mb-3 form-inline">
        <select id="teamsList" class="form-control mt-3 mr-2" name="teams">
            <option value="0">Все</option>
            @foreach($tournament->teams as $team)
                <option value="{{ $team->name }}">{{ $team->name }}</option>
            @endforeach
        </select>
        <select id="roundsList" class="form-control mt-3 mr-2" name="rounds">
            <option value="0">Все туры</option>
            @foreach(array_keys($rounds) as $round)
                <option value="round{{ $round }}">Тур {{ $round }}</option>
            @endforeach
        </select>
        @if(count($divisions) > 1)
            <select id="divisionsList" class="form-control mt-3" name="divisions">
                <option value="0">Все группы</option>
                @foreach($divisions as $division)
                    <option value="division{{ $division }}">Группа {{ TextUtils::divisionLetter($division) }}</option>
                @endforeach
            </select>
        @endif
    </div>

    @foreach($rounds as $round => $divisions)
        <div class="rounds" id="round{{ $round }}">
            <h3>Тур {{ $round }}</h3>
            <div class="row">
                @foreach($divisions as $division => $games)
                    <div class="col-12 divisions division{{ $division }}">
                        @if (count($divisions) > 1)
                            <h4>Группа {{ TextUtils::divisionLetter($division) }}</h4>
                        @endif
                        <table class="table table-striped table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width: 2em;"></th>
                                <th style="width: 8em;">Дата игры</th>
                                <th class="text-right">Хозяева</th>
                                <th class="text-right" style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                                <th style="width: 1em;">:</th>
                                <th style="width: 3em;"><i class="fas fa-hockey-puck"></i></th>
                                <th class="text-left">Гости</th>
                                @auth
                                    <th style="width: 1px;"></th>
                                @endauth
                                <th style="width: 8em;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($games as $game)
                                <tr class="games {{ !is_null($game->home_score) ? TextUtils::gameClass($game->isConfirmed) : '' }}">
                                    <td>
                                        @can('update', $game)
                                            @if(is_null($game->home_score) && $game->gamePlayed)
                                                <i class="fas fa-exclamation-triangle"></i>
                                            @endif
                                        @endcan
                                        <span
                                                class="badge badge-pill badge-warning">{{ $game->isOvertime ? 'О' : '' }}</span>
                                        <span
                                                class="badge badge-pill badge-dark">{{ $game->isShootout ? 'Б' : '' }}</span>
                                        <span
                                                class="badge badge-pill badge-danger">{{ $game->isTechnicalDefeat ? 'T' : '' }}</span>
                                    </td>
                                    <td>
                                        {{ $game->playedAt ? (new \DateTime($game->playedAt))->format('d.m.Y') : '' }}
                                        @if($game->match_id)
                                            <em class="badge badge-secondary">EA</em>
                                        @endif
                                        @can('update', $game)
                                            @if(is_null($game->home_score) && $game->gamePlayed)
                                                <span class="text-muted">{{ $game->gamePlayed }}</span>
                                            @endif
                                        @endcan
                                    </td>
                                    <td class="text-right">
                                        @if ($game->home_score > $game->away_score)
                                            <strong><a
                                                        href="{{ route('team', ['team' => $game->home_team_id]) }}">{{ $game->homeTeam->team->name }}</a></strong>
                                        @else
                                            <a href="{{ route('team', ['team' => $game->home_team_id]) }}">{{ $game->homeTeam->team->name }}</a>
                                        @endif
                                        <span class="badge badge-success">
                                        {{ $game->homeTeam->team->short_name }}
                                    </span>
                                    </td>
                                    <td class="text-right">
                                        {!! !is_null($game->home_score) ? '<span class="badge badge-dark badge-pill">' . $game->home_score . '</span>' : '—' !!}
                                    </td>
                                    <td class="text-center">:</td>
                                    <td class="text-left">
                                        {!! !is_null($game->away_score) ? '<span class="badge badge-dark badge-pill">' . $game->away_score . '</span>' : '—' !!}
                                    </td>
                                    <td class="text-left">
                                    <span class="badge badge-success">
                                        {{ $game->awayTeam->team->short_name }}
                                    </span>
                                        @if ($game->home_score < $game->away_score)
                                            <strong><a
                                                        href="{{ route('team', ['team' => $game->away_team_id]) }}">{{ $game->awayTeam->team->name }}</a></strong>
                                        @else
                                            <a href="{{ route('team', ['team' => $game->away_team_id]) }}">{{ $game->awayTeam->team->name }}</a>
                                        @endif
                                    </td>
                                    @auth
                                        <td class="text-right text-nowrap">
                                            @can('update', $game)
                                                @if(!Auth::user()->isAdmin() && !is_null($game->home_score) && !$game->isConfirmed && $game->getTeamId() !== $game->added_by)
                                                    <button class="btn btn-sm btn-success confirmGame"
                                                            data-id="{{ $game->id }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('group.tournament.regular.game.edit', ['groupTournament' => $tournament->id, 'groupGameRegular' => $game->id]) }}"
                                                   class="btn btn-sm btn-danger"><i class="fas fa-edit"></i></a>
                                            @endcan
                                        </td>
                                    @endauth
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('group.tournament.regular.game', ['groupTournament' => $tournament->id, 'groupGameRegular' => $game->id]) }}">
                                            <i class="fas fa-gamepad"></i> протокол
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            const $teamList = $('#teamsList');

            $teamList.change(function () {
                console.log('fired');
                const $rows = $('.games');
                $rows.show();
                if (this.value === '0') {
                    window.location.hash = '';
                    return;
                }
                window.location.hash = '#' + this.value;
                $rows.not(':contains("' + this.value + '")').hide();
            });

            const $roundsList = $('#roundsList');
            $roundsList.on('change', function () {
                const $rows = $('.rounds');
                const val = $(this).val();
                if (val === '0') {
                    $rows.show();
                    return;
                }

                $rows.hide();
                $('#' + $(this).val()).show();
            });

                    @if(count($divisions) > 1)
            const $divisionsList = $('#divisionsList');
            $divisionsList.on('change', function () {
                const $rows = $('.divisions');
                const val = $(this).val();
                if (val === '0') {
                    $rows.show();
                    return;
                }

                $rows.hide();
                $('.' + $(this).val()).show();
            });
            @endif

            if (window.location.hash) {
                $teamList
                    .val(window.location.hash.replace('#', ''))
                    .trigger('change');
            }

            $('.confirmGame').on('click', function () {
                TRNMNT_helpers.disableButtons();
                const $this = $(this);
                $this.closest('tr').removeClass('table-danger').addClass('table-success');
                const id = +$this.data('id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{ action('Ajax\GroupController@edit',['groupTournament' => $game->tournament_id]) }}/regular/' + id + '/confirm',
                    success: function (response) {
                        TRNMNT_helpers.showNotification(response.message);
                        TRNMNT_helpers.enableButtons();
                        $this.remove();
                    },
                    error() {
                        TRNMNT_helpers.enableButtons();
                    }
                });
            })
        });
    </script>
@endsection
