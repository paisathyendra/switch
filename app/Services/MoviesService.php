<?php

namespace App\Services;

use App\Services\APIService;

class MoviesService
{
    private $apiService;

    public function __construct($apiService = null)
    {
        if(!is_null($apiService) && $apiService instanceof APIService) {
            $this->apiService = $apiService;
        } else {
            $this->apiService = new APIService();
        }
    }

    /*
     * Fetch Movies from End Point
     */
    private function fetchMovies()
    {
        return $this->apiService->fetchData();
    }

    /*
     * Filter movies by Genre & Time
     */
    public function getMoviesByGenreAndTime($genre, $time)
    {
        //All movies
        $movies = $this->fetchMovies();

        //User Input - Genre
        $userGenre = trim(strtolower($genre));
        //User Input - Time
        $userTime = trim($time);

        $data = array();
        //Loop through movies
        foreach ($movies as $movie) {
            //Movie Genres
            $movie_genres = array_map("strtolower", $movie["genres"]);

            //Check for User Genre
            if (in_array($userGenre, $movie_genres)) {
                //Check for User Time
                foreach ($movie["showings"] as $showing) {
                    $movieShowTime = strtotime(str_replace("+11:00", "", $showing));
                    $movieTimeToShow = strtotime('+30 minutes', strtotime($userTime));
                    if (!array_key_exists($movie["name"], $data) && $movieShowTime >= $movieTimeToShow) {
                        $data[$movie["name"]] = array(
                            "movie" => $movie["name"],
                            "time" => date("g:ia", $movieShowTime),
                            "rating" => $movie["rating"]
                        );
                    }
                }
            }
        }

        //Sort By Rating
        usort($data, array($this, "sortByRating"));
        return $data;
    }

    //Sort by rating
    private function sortByRating($a, $b)
    {
        return $b['rating'] - $a['rating'];
    }
}

?>