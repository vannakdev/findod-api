<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('advertisement_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['advertisement_id', 'tag_id']);
            $table->foreign('advertisement_id')->references('id')->on('advertisements');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisement_tags');
    }
}
