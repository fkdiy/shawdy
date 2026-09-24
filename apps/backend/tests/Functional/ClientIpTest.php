<?php

namespace App\Tests\Functional;

class ClientIpTest extends FunctionalTestCase
{
    public function testClientIpIsResolvedFromForwardedHeaders(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/docs',
            server: [
                'REMOTE_ADDR' => '172.19.0.30',
                'HTTP_X_FORWARDED_FOR' => '203.0.113.42',
            ],
        );

        $request = $client->getRequest();

        self::assertSame(
            '203.0.113.42',
            $request->getClientIp()
        );
    }
}
