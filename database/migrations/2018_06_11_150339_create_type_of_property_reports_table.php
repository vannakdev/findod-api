<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeOfPropertyReportsTable extends Migration
{
  /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'type_of_property_reports';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title',100)->nullable(FALSE)->default('');
            $table->string('content')->nullable(FALSE)->default('');
            $table->timestamps();
            $table->unique(['content']);           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->set_schema_table);
    }
}
