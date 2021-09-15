@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if(is_null($tournament))
        {{ Breadcrumbs::render('personal.new') }}
    @else
        {{ Breadcrumbs::render('personal.tournament.edit', $tournament) }}
    @endif

    <h2>{{ $title }}</h2>

    <div class="row">
        <div class="col-lg-5">
            <form id="tournament-form" method="post">
                <div class="form-group">
                    <label for="platform_id">Игровая платформа</label>
                    <select id="platform_id" class="form-control" name="platform_id">
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
                <div class="form-group">
                    <label for="app_id">Игра</label>
                    <select id="app_id" class="form-control" name="app_id">
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
                <div class="form-group">
                    <label for="app_id">Лига</label>
                    <select id="app_id" class="form-control" name="league_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($leagues as $league)
                            <option value="{{ $league->id }}"
                                {{ !is_null($tournament) && $tournament->league_id === $league->id ? 'selected' : '' }}>
                                {{ $league->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Название</label>
                    <input type="text" id="title" class="form-control" name="title"
                           value="{{ !is_null($tournament) ? $tournament->title : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="startedAt">Дата начала турнира</label>
                    <input id="startedAt" class="form-control" name="startedAt" readonly
                           value="{{ !is_null($tournament) ? date('Y-m-d', strtotime($tournament->startedAt)) : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="vk_group_id">Группа Турнира в ВК</label>
                    <input type="text" id="vk_group_id" class="form-control" name="vk_group_id"
                           value="{{ !is_null($tournament) ? $tournament->vk_group_id : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="playoff_rounds">Количество раундов плейоф</label>
                    <select id="playoff_rounds" class="form-control" name="playoff_rounds">
                        <option value="">--Не выбрано--</option>
                        @foreach([1, 2, 3, 4] as $rounds)
                            <option value="{{ $rounds }}"
                                {{ !is_null($tournament) && $tournament->playoff_rounds === $rounds ? 'selected' : '' }}>
                                {{ $rounds }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="playoff_rounds">Матч плей-офф за третье место</label>
                    <select id="playoff_rounds" class="form-control" name="thirdPlaceSeries">
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
                <div class="form-group">
                    <label for="playoff_limit">Количество участников плей-офф</label>
                    <input type="text" id="playoff_limit" class="form-control" name="playoff_limit"
                           value="{{ !is_null($tournament) ? $tournament->playoff_limit : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
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
        <form id="first_place-form" method="post" class="form-inline">
            <input type="hidden" name="place" value="1">
            <div class="form-group mb-2">
                <label for="first_place">Первое место</label>
                <select id="first_place" class="form-control ml-2 mr-2" name="player_id">
                    <option value="0">--Не выбран--</option>
                    @foreach($tournament->players as $player)
                        <option value="{{ $player->id }}"
                                @foreach($tournament->winners as $winner)
                                @if($winner->place === 1 && $winner->player_id === $player->id) selected @endif
                            @endforeach
                        >
                            {{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">
                    Сохранить
                </button>
            </div>
        </form>

        <form id="second_place-form" method="post" class="form-inline">
            <input type="hidden" name="place" value="2">
            <div class="form-group mb-2">
                <label for="second_place">Второе место</label>
                <select id="second_place" class="form-control ml-2 mr-2" name="player_id">
                    <option value="0">--Не выбран--</option>
                    @foreach($tournament->players as $player)
                        <option value="{{ $player->id }}"
                                @foreach($tournament->winners as $winner)
                                @if($winner->place === 2 && $winner->player_id === $player->id) selected @endif
                            @endforeach
                        >
                            {{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">
                    Сохранить
                </button>
            </div>
        </form>

        @if($tournament->thirdPlaceSeries === 1)
            <form id="third_place-form" method="post" class="form-inline">
                <input type="hidden" name="place" value="3">
                <div class="form-group mb-2">
                    <label for="third_place">Третье место</label>
                    <select id="third_place" class="form-control ml-2 mr-2" name="player_id">
                        <option value="0">--Не выбран--</option>
                        @foreach($tournament->players as $player)
                            <option value="{{ $player->id }}"
                                    @foreach($tournament->winners as $winner)
                                    @if($winner->place === 3 && $winner->player_id === $player->id) selected @endif
                                @endforeach
                            >
                                {{ $player->tag }} {{ $player->name ? '(' .  $player->name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
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
                url: '{{ is_null($tournament) ? action('Ajax\PersonalController@create') : action('Ajax\PersonalController@edit', ['personalTournament' => $tournament])}}',
                success: function (response) {
                    window.location.href = '{{ route('personal') }}/' + response.data.id;
                }
            });

            @if (!is_null($tournament))
            TRNMNT_deleteData({
                selector: '#tournament-delete-button',
                url: '{{ action('Ajax\PersonalController@delete', ['personalTournament' => $tournament])}}',
                success: function () {
                    window.location.href = '{{ route('personal') }}';
                }
            });

            TRNMNT_sendData({
                selector: '#first_place-form',
                method: 'post',
                url: '{{ action('Ajax\PersonalController@setWinner', ['personalTournament' => $tournament])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });

            TRNMNT_sendData({
                selector: '#second_place-form',
                method: 'post',
                url: '{{ action('Ajax\PersonalController@setWinner', ['personalTournament' => $tournament])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });

            @if($tournament->thirdPlaceSeries === 1)
            TRNMNT_sendData({
                selector: '#third_place-form',
                method: 'post',
                url: '{{ action('Ajax\PersonalController@setWinner', ['personalTournament' => $tournament])}}',
                success: function (response) {
                    TRNMNT_helpers.showNotification(response.message);
                },
            });
            @endif
            @endif
        });
    </script>
@endsection
