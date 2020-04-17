<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousekeepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('housekeeps', function (Blueprint $table) {
            $table->integer('service_type');//服务类型  例子（保洁，清洗）
            $table->integer('specific_type');//具体类型 例子（保洁：开荒保洁，小时保洁）
            $table->integer('price'); //价格
            $table->string('detailed_address'); //家庭住址
            $table->timestamp('appointment'); //预定时间
            $table->timestamp('pay_time')->nullable(); //支付时间
            $table->integer('unit_id');
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
        Schema::dropIfExists('housekeeps');
    }
}

