<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('floor_category'))
        return;
        Schema::create('floor_category', function (Blueprint $table) {
            $table->engine = 'InnoDB'; 
            $table->increments('id');

            $table->string('rule', 20)->nullable();
            $table->integer('project_name_count')->nullable();
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
        Schema::dropIfExists('floor_category');
    }
}
