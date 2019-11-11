<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('billno');
            $table->integer('user_id');
            $table->integer('status');
            $table->integer('price');
            $table->integer('unit_id');
            $table->char('home_address')->nullable();
            $table->char('arrive_address')->nullable();
            $table->timestamp('get_time')->nullable();
            $table->integer('admin_id')->nullable();
            $table->timestamp('pay_time')->nullable();
            $table->char('arrive_time')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
