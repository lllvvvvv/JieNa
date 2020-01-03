<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoveOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //搬家时间，搬家地点，到达地点，订单状态，接单的人，电话，下单的人,车型
    //1:下单待付款，2:已付款待接单 ,3:已接单,4:已完成
    public function up()
    {
        Schema::create('move_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('moveno');
            $table->string('order_type');
            $table->string('begin_address');
            $table->string('finish_address');
            $table->float('price');
            $table->timestamp('appointment');
            $table->bigInteger('phone');
            $table->integer('user_id');
            $table->integer('driver_id');
            $table->integer('car_type');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('move_orders');
    }
}
