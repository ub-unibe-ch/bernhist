<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
final class DocumentationControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/api/');

        self::assertResponseIsSuccessful();
    }
}
