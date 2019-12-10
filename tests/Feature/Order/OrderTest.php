<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::find(1);
        $this->artisan('db:seed --class=UsersTableSeeder');
        $this->artisan('db:seed --class=UnitsTableSeeder');
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /** @test  */
    public function  newOrder()
    {
        $user = $this->user;
        $response = $this->actingAs($user,'api')
            ->Json('Post','api/newOrder',['unitId'=>1,'status'=>1,"arriveTime"=>"9:00-11:30",
                "arriveAddress"=>"测试地址",
                "boxes"=>[[
                "box_type"=>1,
                    "box_count"=>1]]
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function newOrderNoToken()
    {
        $response = $this->Json('Post','api/newOrder',['unitId'=>1,'status'=>1,"arriveTime"=>"9:00-11:30",
                "arriveAddress"=>"测试地址",
                "boxes"=>[[
                    "box_type"=>1,
                    "box_count"=>1]]
            ]);
        $response->assertStatus(401);
    }

    /** @test */
    public function newOrderNoBoxes()
    {
        $user = $this->user;
        $response = $this->actingAs($user,'api')
            ->Json('Post','api/newOrder',['unitId'=>1,'status'=>1,"arriveTime"=>"9:00-11:30",
            "arriveAddress"=>"测试地址",
            "boxes"=>[]
        ]);
        $response->assertStatus(200)
                ->assertJsonFragment(['message'=>'箱体不足']);
    }


}