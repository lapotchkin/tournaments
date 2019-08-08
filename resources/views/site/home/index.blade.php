@extends('layouts.site')

@section('content')
    <h2>Турниры</h2>
    <ul>
        <li><a href="{{ route('group') }}">Командные турниры</a></li>
        <li><a href="{{ action('Site\PersonalController@index') }}">Турниры 1 на 1</a></li>
    </ul>
@endsection