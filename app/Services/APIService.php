<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class APIService {

	public function connectToAPI() {
		$api_path = config::get('constants.MOVIE_SHOWINGS_API_PATH');
		$client = new \GuzzleHttp\Client(['verify' => false] );
		return $client->request('GET', $api_path);
	}

	public function fetchData() {
		$jsonResponse = $this->connectToAPI();
        return json_decode($jsonResponse->getBody(), true);
	}
}

?>