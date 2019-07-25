<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParentClassControllerTest extends WebTestCase
{
    const USER_NAME = "Test User";
    const USER_LOGIN = "testuser@docker-symfony.de";
    const USER_PW = "qwertz";
    const ADMIN_NAME = "Test Admin";
    const ADMIN_LOGIN = "testadmin@docker-symfony.de";
    const ADMIN_PW = "abcd";

    protected $client;
    protected $crawler;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->loginAsUser();
    }

    public function testParentClassIndexStatusCode200()
    {
        $this->client->request('GET', '/backend/parent');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    protected function loginAsUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();

        $form["email"] = self::USER_LOGIN;
        $form["password"] = self::USER_PW;
        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();
    }
}
