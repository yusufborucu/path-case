<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;

abstract class AbstractTest extends ApiTestCase
{
    private $token;
    private $clientWithCredentials;

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => ['authorization' => 'Bearer '.$token]]);
    }

    protected function getToken($body = []): string
    {
        if ($this->token) {
            return $this->token;
        }

        $response = static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'yusufborucu',
                'password' => '123456'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }

    protected function getOrderId()
    {
        $response = $this->createClientWithCredentials()->request('POST', '/api/order/add', [
            'json' => [                
                'productId' => 1,
                'quantity' => 10,
                'address' => 'sakarya'            
            ]
        ]);

        $data = json_decode($response->getContent());

        return $data->order->id;
    }
}