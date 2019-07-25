<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    const USER_NAME = "Test User";
    const USER_LOGIN = "testuser@docker-symfony.de";
    const USER_PW = "qwertz";
    const ADMIN_NAME = "Test Admin";
    const ADMIN_LOGIN = "testadmin@docker-symfony.de";
    const ADMIN_PW = "abcd";

    public function testLoginPageStatusOk()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testBackendWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/backend');
        $crawler = $client->followRedirect();

        $this->assertContains(
            "Please login",
            $crawler->filter('h3')->text()
        );
    }

    public function testBackendLoginWithWrongCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

        $form["email"] = "qwertz@foobar.de";
        $form["password"] = "qwertz";

        $client->submit($form);
        $crawler = $client->followRedirect();

        // $client->getResponse()->getStatusCode always gives a 200 ...
        $this->assertContains(
            "Invalid credentials",
            $crawler->filter('div')->text()
        );
    }

    public function testBackendLoginWithCorrectCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

        $form["email"] = self::USER_LOGIN;
        $form["password"] = self::USER_PW;

        $client->submit($form);
        $client->followRedirect();

        $this->assertContains(
            self::USER_NAME,
            $client->getResponse()->getContent()
        );
    }

    public function testBackendLoginAsAdmin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

        $form["email"] = self::ADMIN_LOGIN;
        $form["password"] = self::ADMIN_PW;

        $client->submit($form);
        $crawler = $client->followRedirect();

        $node = $crawler->filterXPath('//a[@id="admin-link"]');
        $this->assertTrue($node->count() == 1);
    }

    public function testBackendLoginNoAdminNoAdminLink()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

        $form["email"] = self::USER_LOGIN;
        $form["password"] = self::USER_PW;

        $client->submit($form);
        $crawler = $client->followRedirect();

        $node = $crawler->filterXPath('//a[@id="admin-link"]');
        $this->assertFalse($node->count() == 1);
    }
}
