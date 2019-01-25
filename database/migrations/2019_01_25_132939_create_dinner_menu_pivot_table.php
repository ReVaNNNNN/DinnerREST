<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDinnerMenuPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dinner_menu', function (Blueprint $table) {
            $table->integer('dinner_id')->unsigned()->index();
            $table->foreign('dinner_id')->references('id')->on('dinners')->onDelete('cascade');
            $table->integer('menu_id')->unsigned()->index();
            $table->foreign('menu_id')->references('id')->on('menu')->onDelete('cascade');
            $table->primary(['dinner_id', 'menu_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dinner_menu');
    }
}
