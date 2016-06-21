<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache_data', function (Blueprint $table) {
            $table->increments('id');
            $table->char('session_id', 255)->index();
            $table->char('entity', 255)->index();
            $table->char('type', 255)->index();
            $table->string('method', 255)->index();
            $table->integer('vk_id');
            $table->json('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cache_data');
    }
}
