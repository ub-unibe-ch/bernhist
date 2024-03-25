<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class PageControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Impressum');
    }

    public function testWhatIs(): void
    {
        $client = static::createClient();
        $client->request('GET', '/whatis/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Was ist BERNHIST');
    }

    public function testHistory(): void
    {
        $client = static::createClient();
        $client->request('GET', '/history/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Geschichte von BERNHIST');
    }

    public function testLiterature(): void
    {
        $client = static::createClient();
        $client->request('GET', '/literature/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Literatur');
    }

    public function testContact(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Kontakt');
    }
}
