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

        $timeData = explode(":", $time);

        if(count($timeData) != 2 || $timeData[0] > 23) {
            return redirect()->back()->withInput()->with('msg', "Invalid Time. Please use valid Time format. Example: 14:00");
        }
        //Recommended Movies
        $movieService = new MoviesService();
        $recommendedMovies = $movieService->getMoviesByGenreAndTime($genre, $time);

        return view("recommended", ['recommendedMovies' => $recommendedMovies]);
    }
}
