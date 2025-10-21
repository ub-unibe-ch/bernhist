<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
final class PageControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Impressum');
    }

    public function testWhatIs(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/whatis/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Was ist BERNHIST');
    }

    public function testHistory(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/history/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Geschichte von BERNHIST');
    }

    public function testLiterature(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/literature/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Literatur');
    }

    public function testContact(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/contact/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Kontakt');
    }
}
