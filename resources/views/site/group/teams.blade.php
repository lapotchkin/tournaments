@extends('layouts.site')

@section('title', $tournament->title . ' — ')

@section('content')
    {{ Breadcrumbs::render('group.tournament', $tournament) }}
    @widget('groupHeader', ['tournament' => $tournament])
    @widget('groupMenu', ['tournament' => $tournament])

    <h3>Команды</h3>
    <div class="card-deck">
        @foreach($divisions as $number => $division)
            <div class="card mb-3">
                <h4 class="card-header">Группа {{ TextUtils::divisionLetter($number) }}</h4>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <tbody>
                        @foreach($division as $team)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ action('Site\TeamController@team', ['teamId' => $team->id]) }}">
                                        {{ $team->name }}
                                    </a>
                                </td>
                                <td class="text-right">
                                    @auth
                                        @if(Auth::user()->isAdmin())
                                            <a class="btn btn-primary btn-sm"
                                               href="{{ action('Site\GroupController@team', ['tournamentId' => $tournament->id, 'teamId' => $team->id]) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection