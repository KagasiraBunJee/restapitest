<?php

namespace RestApi\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeviceControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

    public function testBroadcast()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/broadcast');
    }

    public function testBroadcasttoall()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/broadcastToAll');
    }

}
