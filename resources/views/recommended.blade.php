@extends('master')

@section('content')
    @if (count($recommendedMovies) == 0)
        No movie recommendations
    @endif
    <ul>
    @foreach($recommendedMovies as $movie)
        <li><b>{{$movie['movie']}}</b>, showing at {{$movie['time']}}</li>
    @endforeach
    </ul>
@endsection