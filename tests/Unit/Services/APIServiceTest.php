<?php

namespace Tests\Unit\Services;

use Symfony\Component\Yaml\Tests\A;
use Tests\TestCase;
use App\Services\APIService;

class APIServiceTest extends TestCase
{
    public function testShouldReturn200Status()
    {
        $apiSerivce = new APIService();
        $response = $apiSerivce->connectToAPI();
        $this->assertEquals("200", $response->getStatusCode());
    }
}

?>