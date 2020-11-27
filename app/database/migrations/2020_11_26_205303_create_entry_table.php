<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->string('youtuber_id');
            $table->foreign('youtuber_id')->references('id')->on('youtuber');
            $table->string('title');
            $table->text('description');
            $table->string('link');
            $table->string('thumbnail');
            $table->bigInteger('views');
            $table->dateTime('published_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry');
    }
}
