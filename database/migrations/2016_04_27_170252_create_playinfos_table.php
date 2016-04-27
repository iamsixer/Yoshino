<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->index();
            $table->string('activityId');
            $table->integer('videoId');
            $table->string('videoUnique');
            $table->integer('ctime');
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
        Schema::drop('playinfos');
    }
}
