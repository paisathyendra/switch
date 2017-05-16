<?php

namespace App\Services;

use App\Services\APIService;

class MoviesService
{
    private $apiService;

    public function __construct($apiService = null)
    {
        if (!is_null($apiService) && $apiService instanceof APIService) {
            $this->apiService = $apiService;
        } else {
            $this->apiService = new APIService();
        }
    }

    /**
     * Fetch Movies from end point
     * @return mixed
     */
    private function fetchMovies()
    {
        return $this->apiService->fetchData();
    }

    /**
     * Filter movies by Genre & Time
     * @param $genre
     * @param $time
     * @return array
     */
    public function getMoviesByGenreAndTime($genre, $time)
    {
        //All movies
        $movies = $this->fetchMovies();
        //User Input - Genre
        $userGenre = trim(strtolower($genre));
        //User Input - Time
        $userTime = trim($time);

        $data = $this->filterMovies($movies, $userGenre, $userTime);

        //Sort By Rating
        usort($data, array($this, "sortByRating"));
        return $data;
    }

    /**
     * Filter Movies
     * @param $movies
     * @param $userGenre
     * @param $userTime
     * @return array
     */
    public function filterMovies($movies, $userGenre, $userTime)
    {
        $data = array();
        //Loop through movies
        foreach ($movies as $movie) {
            //Movie Genres
            $movie_genres = array_map("strtolower", $movie["genres"]);

            //Check for User Genre
            $isGenreExists = in_array($userGenre, $movie_genres);
            if ($isGenreExists) {

                //Check for User Time
                foreach ($movie["showings"] as $showing) {

                    $movieShowTime = strtotime(str_replace("+11:00", "", $showing));
                    $movieTimeToShow = strtotime('+30 minutes', strtotime($userTime));

                    $isRecommended = !array_key_exists($movie["name"], $data) && $movieShowTime >= $movieTimeToShow;
                    if ($isRecommended) {
                        $this->formResponse($movie, $movieShowTime, $data);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Form Response
     * @param $movie
     * @param $movieShowTime
     * @param $data
     */
    public function formResponse($movie, $movieShowTime, &$data)
    {
        $response = array(
            "movie" => $movie["name"],
            "time" => date("g:ia", $movieShowTime),
            "rating" => $movie["rating"]
        );
        $data[$movie["name"]] = $response;
    }

    //Sort by rating
    private function sortByRating($a, $b)
    {
        return $b['rating'] - $a['rating'];
    }
}

?>