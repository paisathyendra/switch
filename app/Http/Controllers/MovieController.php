<?php

namespace App\Http\Controllers;

use App\Services\MoviesService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return view("index");
    }

    public function recommendedMovies(Request $request)
    {
        //User Inputs
        $genre = $request->input('genre');
        $time = $request->input('time');

        //Recommended Movies
        $movieService = new MoviesService();
        $recommendedMovies = $movieService->getMoviesByGenreAndTime($genre, $time);

        return view("recommended", ['recommendedMovies' => $recommendedMovies]);
    }
}
