@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if(is_null($tournament))
        {{ Breadcrumbs::render('group.new') }}
    @else
        {{ Breadcrumbs::render('group.tournament.edit', $tournament) }}
    @endif

    <h2>{{ $title }}</h2>

    <div class="row">
        <div class="col-lg-5">
            <form id="tournament-form" method="post">
                <div class="mb-3">
                    <label for="platform_id" class="form-label">Игровая платформа</label>
                    <select id="platform_id" class="form-select" name="platform_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}"
                                {{ !is_null($tournament) && $tournament->platform_id === $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="app_id" class="form-label">Игра</label>
                    <select id="app_id" class="form-select" name="app_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($apps as $app)
                            <option value="{{ $app->id }}"
                                {{ !is_null($tournament) && $tournament->app_id === $app->id ? 'selected' : '' }}>
                                {{ $app->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Название</label>
                    <input type="text" id="title" class="form-control" name="title"
                           value="{{ !is_null($tournament) ? $tournament->title : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="vk_group_id" class="form-label">Группа Турнира в ВК</label>
                    <input type="text" id="vk_group_id" class="form-control" name="vk_group_id"
                           value="{{ !is_null($tournament) ? $tournament->vk_group_id : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="startedAt" class="form-label">Дата начала турнира</label>
                    <input id="startedAt" class="form-control" name="startedAt" readonly
                           value="{{ !is_null($tournament) ? date('Y-m-d', strtotime($tournament->startedAt)) : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="min_players" class="form-label">Минимальное количество игроков в команде</label>
                    <select id="min_players" class="form-select" name="min_players">
                        <option value="">--Не выбрано--</option>
                        @foreach([3, 6] as $count)
                            <option value="{{ $count }}"
                                {{ !is_null($tournament) && $tournament->min_players === $count ? 'selected' : '' }}>
                                {{ $count }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="playoff_rounds" class="form-label">Количество раундов плейоф</label>
                    <select id="playoff_rounds" class="form-select" name="playoff_rounds">
                        <option value="">--Не выбрано--</option>
                        @foreach([1, 2, 3, 4] as $rounds)
                            <option value="{{ $rounds }}"
                                {{ !is_null($tournament) && $tournament->playoff_rounds === $rounds ? 'selected' : '' }}>
                                {{ $rounds }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="playoff_rounds" class="form-label">Матч плей-офф за третье место</label>
                    <select id="playoff_rounds" class="form-select" name="thirdPlaceSeries">
                        <option value="0"
                            {{ !is_null($tournament) && $tournament->thirdPlaceSeries !== 1 ? 'selected' : '' }}>
                            Нет
                        </option>
                        <option value="1"
                            {{ !is_null($tournament) && $tournament->thirdPlaceSeries === 1 ? 'selected' : '' }}>
                            Да
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="playoff_limit" class="form-label">Количество участников плей-офф</label>
                    <input type="text" id="playoff_limit" class="form-control" name="playoff_limit"
                           value="{{ !is_null($tournament) ? $tournament->playoff_limit : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        {{ is_null($tournament) ? 'Добавить' : 'Принять изменения' }}
                    </button>
                    @if (!is_null($tournament))
                        <button type="button" class="btn btn-danger" id="tournament-delete-button">
                            Удалить
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if (!is_null($tournament))
        <form id="first_place-form" method="post" class="row row-cols-lg-auto g-2 align-items-center mt-2">
            <input type="hidden" name="place" value="1">
            <div class="col-12">
                <div class="input-group">
                    <label for="first_place" class="input-group-text">Первое место</label>
                    <select id="first_place" class="form-select" name="team_id">
                        <option value="0">--Не выбрана--</option>
                        @foreach($tournament->teams as $team)
                            <option value="{{ $team->id }}"
                                    @foreach($tournament->winners as $winner)
                                    @if($winner->place === 1 && $winner->team_id === $team->id) selected @endif
                                @endforeach
                            >
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>

        <form id="second_place-form" method="post" class="row row-cols-lg-auto g-2 align-items-center mt-1">
            <input type="hidden" name="place" value="2">
            <div class="col-12">
                <div class="input-group mr-3">
                    <label for="second_place" class="input-group-text">Второе место</label>
                    <select id="second_place" class="form-select" name="team_id">
                        <option value="0">--Не выбрана--</option>
                        @foreach($tournament->teams as $team)
                            <option value="{{ $team->id }}"
                                    @foreach($tournament->winners as $winner)
                                    @if($winner->place === 2 && $winner->team_id === $team->id) selected @endif
                                @endforeach
                            >
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    Сохранить
                </button>
            </div>
        </form>

        @if($tournament->thirdPlaceSeries === 1)
            <form id="third_place-form" method="post" class="row row-cols-lg-auto g-2 align-items-center mt-1">
                <input type="hidden" name="place" value="3">
                <div class="col-12">
                    <div class="input-group">
                        <label for="third_place" class="input-group-text">Третье место</label>
                        <select id="third_place" class="form-select" name="team_id">
                            <option value="0">--Не выбрана--</option>
                            @foreach($tournament->teams as $team)
                                <option value="{{ $team->id }}"
                                        @foreach($tournament->winners as $winner)
                                        @if($winner->place === 3 && $winner->team_id === $team->id) selected @endif
                                    @endforeach
                                >
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        Сохранить
                    </button>
                </div>
            </form>
        @endif
    @endif
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $('#startedAt').datepicker(TRNMNT_helpers.getDatePickerSettings());

            TRNMNT_sendData({
                selector: '#tournament-form',
                method: '{{ is_null($tournament) ? 'put' : 'post' }}',
                url: '{{ is_null($tournament) ? action('Ajax\GroupController@create') : action('Ajax\GroupController@edit', ['groupTournament' => $tournament->id])}}',
                success: function (response) {
                    window.location.href = '{{ route('group') }}/' + response.data.id;
                }
            });

            @if (!is_null($tournament))
            TRNMNT_deleteData({
                selector: '#tournament-delete-button',
                url: '{{ action('Ajax\GroupController@delete', ['groupTournament' => $tournament->id])}}',
                success: function () {
                    window.location.href = '{{ route('group') }}';
                }
            });

            TRNMNT_sendData({
                selector: '#first_place-form',
                method: 'post',
                url: '{{ action('Ajax\GroupController@setWinner', ['groupTournament' => $tournament->id])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });

            TRNMNT_sendData({
                selector: '#second_place-form',
                method: 'post',
                url: '{{ action('Ajax\GroupController@setWinner', ['groupTournament' => $tournament->id])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });

            @if($tournament->thirdPlaceSeries === 1)
            TRNMNT_sendData({
                selector: '#third_place-form',
                method: 'post',
                url: '{{ action('Ajax\GroupController@setWinner', ['groupTournament' => $tournament->id])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });
            @endif
            @endif
        });
    </script>
@endsection
