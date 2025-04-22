<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'POST', 
            uri: '/orders', 
            content: json_encode([
                'items'=> [
                    [
                        'productId' => 1,
                        'productName' => 'test',
                        'quantity' => 1,
                        'price' => 100
                    ]
                ],
            ]),
        );

        self::assertResponseIsSuccessful();
    }
}
