<?php

namespace Tests\Feature;

use App\User;

use Tests\TestCase;

class NewOrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::find(2);
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /** @test  */
    public function  getOrder()
    {
        $response = $this->actingAs($this->user,'api')
            ->json('Get','api/getOrders');
        $response->assertStatus(200);
    }

}