<?php

namespace Tests\Unit;

use App\Services\PriceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOneDay()
    {
        $result = new PriceService();
        $begin_time = "2019-11-28 00:00:00";
        $end_time = "2019-11-28 23:59:59";
        $this->assertEquals(5,$result->timeCount($begin_time,$end_time));
        $result = new PriceService();
        $begin_time = "2019-11-21 10:00:00";
        $end_time = "2019-11-21 10:15:00";
        $this->assertEquals(1,$result->timeCount($begin_time,$end_time));
    }

    public function testTwoDay()
    {
        $result = new PriceService();
        $begin_time = "2019-11-28 01:00:00";
        $end_time = "2019-11-29 00:00:00";
        $this->assertEquals(6,$result->timeCount($begin_time,$end_time));
    }

    public function testMoreTime()
    {
        $result = new PriceService();
        $begin_time = "2019-11-21 00:00:00";
        $end_time = "2019-11-24 09:00:00";
        $this->assertEquals(18,$result->timeCount($begin_time,$end_time));
    }

    public function testTime()
    {
        $result = new PriceService();
        $begin_time = "2019-11-21 10:51:59";
        $end_time = "2019-11-25 21:00:00";
        $this->assertEquals(24,$result->timeCount($begin_time,$end_time));
        $begin_time = "2019-11-20 15:51:59";
        $end_time = "2019-11-25 09:59:59";
        $this->assertEquals(26,$result->timeCount($begin_time,$end_time));
    }

    public function testFree()
    {
        $result = new PriceService();
        $begin_time = "2019-11-21 10:00:00";
        $end_time = "2019-11-21 10:14:59";
        $this->assertEquals(0,$result->timeCount($begin_time,$end_time));
    }

}
