<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('floor_category_translations')) {
            return;
        }
        Schema::create('floor_category_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('floor_category_id')->unsigned();
            $table->char('locale', 20)->index();
            $table->string('title', 50);
            $table->nullableTimestamps();

            $table->unique(['floor_category_id', 'locale']);
            $table->foreign('floor_category_id')->references('id')->on('floor_category')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('floor_category_translations');
    }
}
