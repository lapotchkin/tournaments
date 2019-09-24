@extends('layouts.site')

@section('title', $title . ' — ')

@section('content')
    @if ($team)
        {{ Breadcrumbs::render('team.edit', $team) }}
    @else
        {{ Breadcrumbs::render('team.add') }}
    @endif

    <h2>{{ $title }}</h2>

    <div class="row">
        <div class="col-md-8 col-lg-5">
            <form id="team-form" method="post">
                <div class="form-group">
                    <label for="platform_id">Игровая платформа</label>
                    <select id="platform_id" class="form-control" name="platform_id">
                        <option value="">--Не выбрана--</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}"
                                {{ !is_null($team) && $team->platform_id === $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" id="name" class="form-control" name="name"
                           value="{{ !is_null($team) ? $team->name : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="short_name">Краткое название</label>
                    <input type="text" id="short_name" class="form-control" name="short_name"
                           value="{{ !is_null($team) ? $team->short_name : '' }}">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ is_null($team) ? 'Добавить' : 'Принять изменения' }}
                    </button>
                    @if (!is_null($team))
                        <button type="button" class="btn btn-danger" id="team-delete-button">
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
                selector: '#team-form',
                method: '{{ is_null($team) ? 'put' : 'post' }}',
                url: '{{ is_null($team) ? action('Ajax\TeamController@create') : action('Ajax\TeamController@edit', ['teamId' => $team->id])}}',
                success: function (response) {
                    window.location.href = '{{ route('teams') }}/' + response.data.id + '/edit';
                }
            });

            @if (!is_null($team))
            TRNMNT_deleteData({
                selector: '#team-delete-button',
                url: '{{ action('Ajax\TeamController@delete', ['teamId' => $team->id])}}',
                success: function () {
                    window.location.href = '{{ route('teams') }}';
                }
            });
            @endif
        });
    </script>
@endsection
