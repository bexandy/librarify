<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BooksControllerTest extends WebTestCase
{
    public function testCreateBookInvalidData()
    {
        $client = static::createClient();

        $client->request(
        	'POST', 
        	'/api/books',
        	[],
        	[],
        	['CONTENT_TYPE' => 'application/json'],
        	'{"title":""}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateBookEmptyData()
    {
        $client = static::createClient();

        $client->request(
        	'POST', 
        	'/api/books',
        	[],
        	[],
        	['CONTENT_TYPE' => 'application/json'],
        	''
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $client = static::createClient();

        $client->request(
        	'POST', 
        	'/api/books',
        	[],
        	[],
        	['CONTENT_TYPE' => 'application/json'],
        	'{"title":"El imperio final"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}