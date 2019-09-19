@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if ($player)
        {{ Breadcrumbs::render('player.edit', $player) }}
    @else
        {{ Breadcrumbs::render('player.add') }}
    @endif

    <h2>{{ $title }}</h2>

    <div class="row">
        <div class="col-md-8 col-lg-5">
            <form id="player-form" method="post">
                <div class="form-group">
                    <label for="platform_id">Игровая платформа</label>
                    <select id="platform_id" class="form-control" name="platform_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}"
                                {{ !is_null($player) && $player->platform_id === $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Игровой тэг</label>
                    <input type="text" id="tag" class="form-control" name="tag"
                           value="{{ !is_null($player) ? $player->tag : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Имя</label>
                    <input type="text" id="name" class="form-control" name="name"
                           value="{{ !is_null($player) ? $player->name : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">ID Вконтакет</label>
                    <input type="text" id="vk" class="form-control" name="vk"
                           value="{{ !is_null($player) ? $player->vk : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Город</label>
                    <input type="text" id="city" class="form-control" name="city"
                           value="{{ !is_null($player) ? $player->city : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Широта для карты</label>
                    <input type="text" id="lat" class="form-control" name="lat"
                           value="{{ !is_null($player) ? $player->lat : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="title">Долгота для карты</label>
                    <input type="text" id="lon" class="form-control" name="lon"
                           value="{{ !is_null($player) ? $player->lon : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ is_null($player) ? 'Добавить' : 'Принять изменения' }}
                    </button>
                    @if (!is_null($player))
                        <button type="button" class="btn btn-danger" id="player-delete-button">
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
                selector: '#player-form',
                method: '{{ is_null($player) ? 'put' : 'post' }}',
                url: '{{ is_null($player) ? action('Ajax\PlayerController@create') : action('Ajax\PlayerController@edit', ['playerId' => $player->id])}}',
                success: function (response) {
                    window.location.href = '{{ route('players') }}/' + response.data.id;
                }
            });

            @if (!is_null($player))
            TRNMNT_deleteData({
                selector: '#player-delete-button',
                url: '{{ action('Ajax\PlayerController@delete', ['playerId' => $player->id])}}',
                success: function () {
                    window.location.href = '{{ route('players') }}';
                }
            });
            @endif
        });
    </script>
@endsection
