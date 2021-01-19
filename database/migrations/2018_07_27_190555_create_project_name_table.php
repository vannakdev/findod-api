<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('project_name')) {
            return;
        }
        Schema::create('project_name', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('lat', 11)->default(0);
            $table->string('lng', 11)->default(0);

            $table->string('address', 200)->nullable();
            $table->string('hotline', 20)->nullable();
            $table->integer('tower')->unsigned()->nullable();
            $table->integer('floor')->unsigned()->nullable();
            $table->integer('floor_category_id')->unsigned()->nullable();

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('project_name');
        Schema::enableForeignKeyConstraints();
    }
}
