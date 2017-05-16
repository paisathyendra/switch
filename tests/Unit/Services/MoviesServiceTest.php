<?php

namespace App\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use App\Services\MoviesService;
use Illuminate\Support\Facades\Config;

class MoviesServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $app = new Application();
        $app->singleton('app', Application::class);
        Facade::setFacadeApplication($app);
    }

    public function testShouldNotReturnMoviesIfNoGenreIsMatching()
    {
        $genre = "Animation";
        $time = "12:00";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);
        $this->assertEmpty($movies);
    }

    public function testShouldReturnMoviesIfGenreIsMatching()
    {
        $genre = "Drama";
        $time = "12:00";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(1, $movies);
        $this->assertEquals("Moonlight", $movies[0]["movie"]);
    }

    public function testShouldReturnMultipleMoviesInRatingsOrder()
    {
        $genre = "Drama";
        $time = "12:00";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        ),
            1 => array(
                "name" => "Bahubali",
                "rating" => 100,
                "genres" => Array(0 => "Animation", 1 => "Drama"),
                "showings" => Array(
                    0 => "18:30:00+11:00",
                    1 => "20:30:00+11:00"
                )
            )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(2, $movies);
        $this->assertEquals("Bahubali", $movies[0]["movie"]);
        $this->assertEquals("Moonlight", $movies[1]["movie"]);
    }

    public function testShouldReturnMoviesIfMultipleGenreIsPresent()
    {
        $genre = "Animation";
        $time = "12:00";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama", 1 => "Animation"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(1, $movies);
        $this->assertEquals("Moonlight", $movies[0]["movie"]);
    }

    public function testShouldReturnMoviesOnlyIfItStartAfter30MinutesOfUserInput()
    {
        $genre = "Animation";
        $time = "18:30";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama", 1 => "Animation"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(1, $movies);
        $this->assertEquals("Moonlight", $movies[0]["movie"]);
    }

    public function testShouldNotReturnMoviesIfItStartsBefore30MinutesOfUserInput()
    {
        $genre = "Animation";
        $time = "20:01";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama", 1 => "Animation"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(0, $movies);
    }

    public function testShouldReturnMoviesEvenIfTheUserInputIsInLowercase()
    {
        $genre = "animation";
        $time = "18:30";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama", 1 => "Animation"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(1, $movies);
        $this->assertEquals("Moonlight", $movies[0]["movie"]);
    }

    public function testShouldReturnOnlyLatestEntryForOneMovieIfThereAreMultipleShowTimes()
    {
        $genre = "Animation";
        $time = "18:00";

        $movies = array(0 => array(
            "name" => "Moonlight",
            "rating" => 98,
            "genres" => Array(0 => "Drama", 1 => "Animation"),
            "showings" => Array(
                0 => "18:30:00+11:00",
                1 => "20:30:00+11:00"
            )
        )
        );

        $mockAPIService = $this->createMock(APIService::class);
        $mockAPIService->method('fetchData')
            ->willReturn($movies);

        $moviesService = new MoviesService($mockAPIService);
        $movies = $moviesService->getMoviesByGenreAndTime($genre, $time);

        $this->assertCount(1, $movies);
        $this->assertEquals("6:30pm", $movies[0]['time']);
    }
}
