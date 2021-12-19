<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompareControllerTest extends WebTestCase
{
    public function testCompareController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/?first=symfony/symfony&second=laravel/laravel');
        $response = $client->getResponse();
        $success = $response->isSuccessful();

        $this->assertTrue($success);
    }
}
