<?php

namespace RestApi\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/create');
    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/view');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/list');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/delete');
    }

}
