<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class OrderTest extends AbstractTest
{
    public function testAddOrder(): void
    {
        $response = $this->createClientWithCredentials()->request('POST', '/api/order/add', [
            'json' => [                
                'productId' => 1,
                'quantity' => 10,
                'address' => 'sakarya'            
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testGetAllOrders(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/order/all');

        $this->assertResponseIsSuccessful();
    }

    public function testGetOrder(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/order/get/' . $this->getOrderId());

        $this->assertResponseIsSuccessful();
    }    

    public function testUpdateOrder(): void
    {
        $response = $this->createClientWithCredentials()->request('PUT', '/api/order/update/' . $this->getOrderId(), [
            'json' => [                
                'productId' => 2,
                'quantity' => 20,
                'address' => 'serdivan/sakarya'            
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteOrder(): void
    {
        $response = $this->createClientWithCredentials()->request('DELETE', '/api/order/delete/' . $this->getOrderId());

        $this->assertResponseIsSuccessful();
    }
}
