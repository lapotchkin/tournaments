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
                <div class="form-group">
                    <label for="platform_id">ID игровой платформы</label>
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
                    <label for="app_id">ID игры</label>
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
                    <label for="title">Название</label>
                    <input type="text" id="title" class="form-control" name="title"
                           value="{{ !is_null($tournament) ? $tournament->title : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="min_players">Минимальное количество игроков в команде</label>
                    <select id="min_players" class="form-control" name="min_players">
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
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            TRNMNT_sendData({
                selector: '#tournament-form',
                method: '{{ is_null($tournament) ? 'put' : 'post' }}',
                url: '{{ is_null($tournament) ? action('Ajax\GroupController@create') : action('Ajax\GroupController@edit', ['tournamentId' => $tournament->id])}}',
                success: function (response) {
                    window.location.href = '{{ action('Site\GroupController@index') }}/' + response.data.id;
                }
            });

            @if (!is_null($tournament))
            TRNMNT_deleteData({
                selector: '#tournament-delete-button',
                url: '{{ action('Ajax\GroupController@delete', ['tournamentId' => $tournament->id])}}',
                success: function () {
                    window.location.href = '{{ action('Site\GroupController@index') }}';
                }
            });
            @endif
        });
    </script>
@endsection