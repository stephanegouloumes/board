<?php

namespace App\Tests\Features;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Features\DatabasePrimer;

class BoardTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/board/');

        // dd($client->getResponse()->getContent()['boards']);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Board index', $crawler->filter('h1')->text());
    }
}
