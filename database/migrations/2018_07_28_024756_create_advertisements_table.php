<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255)->nullable(false)->default('');
            $table->string('feature_image',255)->nullable(false)->default('');
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            $table->integer('user_id')->unsigned();
            $table->text('content')->default('');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });


  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('advertisements');
        Schema::enableForeignKeyConstraints();
    }
}
