<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComponentDinnerPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_dinner', function (Blueprint $table) {
            $table->integer('component_id')->unsigned()->index();
            $table->foreign('component_id')->references('id')->on('components')->onDelete('cascade');
            $table->integer('dinner_id')->unsigned()->index();
            $table->foreign('dinner_id')->references('id')->on('dinners')->onDelete('cascade');
            $table->primary(['component_id', 'dinner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('component_dinner');
    }
}
