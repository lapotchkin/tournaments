@extends('layouts.site')

@section('title', $tournament->title . ': Чемпионат (Расписание) — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament.regular.games', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])
    @widget('groupRegularMenu', ['tournament' => $tournament])

    <div class="mb-3 w-25">
        <select id="teamsList" class="form-control mt-3" name="teams">
            <option value="0">Все</option>
            @foreach($tournament->teams as $team)
                <option value="{{ $team->name }}">{{ $team->name }}</option>
            @endforeach
        </select>
    </div>

    @foreach($rounds as $round => $divisions)
        <h3>Тур {{ $round }}</h3>
        <div class="row">
            @foreach($divisions as $division => $games)
                <div class="col-12">
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
                            <th style="width: 8em;"></th>
                            @auth
                                <th style="width: 2em;"></th>
                            @endauth
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($games as $game)
                            <tr class="games {{ !is_null($game->home_score) ? 'table-success' : '' }}">
                                <td>
                                    <span
                                        class="badge badge-pill badge-warning">{{ $game->isOvertime ? 'О' : '' }}</span>
                                    <span class="badge badge-pill badge-dark">{{ $game->isShootout ? 'Б' : '' }}</span>
                                    <span
                                        class="badge badge-pill badge-danger">{{ $game->isTechnicalDefeat ? 'T' : '' }}</span>
                                </td>
                                <td>
                                    {{ $game->playedAt ? (new \DateTime($game->playedAt))->format('d.m.Y') : '' }}
                                    @if($game->match_id)
                                        <em class="badge badge-secondary">EA</em>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($game->home_score > $game->away_score)
                                        <strong><a href="{{ route('team', ['teamId' => $game->home_team_id]) }}">{{ $game->homeTeam->team->name }}</a></strong>
                                    @else
                                        <a href="{{ route('team', ['teamId' => $game->home_team_id]) }}">{{ $game->homeTeam->team->name }}</a>
                                    @endif
                                    <span class="badge badge-success badge-pill">
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
                                    <span class="badge badge-success badge-pill">
                                        {{ $game->awayTeam->team->short_name }}
                                    </span>
                                    @if ($game->home_score < $game->away_score)
                                        <strong><a href="{{ route('team', ['teamId' => $game->away_team_id]) }}">{{ $game->awayTeam->team->name }}</a></strong>
                                    @else
                                        <a href="{{ route('team', ['teamId' => $game->away_team_id]) }}">{{ $game->awayTeam->team->name }}</a>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-primary"
                                       href="{{ route('group.tournament.regular.game', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}">
                                        <i class="fas fa-gamepad"></i> протокол
                                    </a>
                                </td>
                                @auth
                                    <td class="text-right">
                                        <a href="{{ route('group.tournament.regular.game.edit', ['tournamentId' => $tournament->id, 'gameId' => $game->id]) }}"
                                           class="btn btn-sm btn-danger"><i class="fas fa-edit"></i></a>
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            var $teamList = $('#teamsList');

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

            console.log(window.location.hash);

            if (window.location.hash) {
                $teamList
                    .val(window.location.hash.replace('#', ''))
                    .trigger('change');
            }
        });
    </script>
@endsection
