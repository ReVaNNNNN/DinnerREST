<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDinnerPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dinner', function (Blueprint $table) {
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('dinner_id')->unsigned();
            $table->foreign('dinner_id')->references('id')->on('dinner')->onDelete('cascade');
            $table->primary(['dinner_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_dinner');
    }
}
