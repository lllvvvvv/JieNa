<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillnoHouskeepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('housekeeps',function (Blueprint $table)
        {
            $table->string('billno');
            $table->integer('order_status')->default(null);
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('housekeeps',function (Blueprint $table)
        {
            $table->string('billno');
            $table->integer('order_status')->default(null);
            $table->integer('user_id');
        });
    }
}
