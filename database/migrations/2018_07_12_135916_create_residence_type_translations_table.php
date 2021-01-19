<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenceTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('residence_type_translations');
        Schema::create('residence_type_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('residence_type_id')->unsigned();
            $table->char('locale', 20)->index();
            $table->string('title', 200);
            $table->nullableTimestamps();

            $table->unique(['residence_type_id', 'locale']);
            $table->foreign('residence_type_id')->references('id')->on('residence_type')
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
        Schema::dropIfExists('residence_type_translations');
    }
}
