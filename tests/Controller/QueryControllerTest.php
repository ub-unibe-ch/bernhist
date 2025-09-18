<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class QueryControllerTest extends WebTestCase
{
    public function testQuery(): void
    {
        $client = static::createClient();

        $client->request('GET', '/query/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Ortsauswahl');
        self::assertSelectorExists('#level-16');
        self::assertSelectorTextContains('#level-16 > a', 'Bern (Kanton)');
    }

    public function testQueryEntry(): void
    {
        $client = static::createClient();

        $client->request('GET', '/query/location/434/topic/990/1856-1980/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body > main > h1.d-print-none', 'Resultate');
        self::assertSelectorTextContains('h2', '14 Resultate gefunden');
    }
}
