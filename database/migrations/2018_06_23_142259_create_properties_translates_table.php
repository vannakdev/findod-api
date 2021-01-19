<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTranslatesTable extends Migration {

    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'properties_translations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable($this->set_schema_table))
            return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('properties_id')->unsigned();
            $table->char('locale', 20)->index();
            $table->string('pro_title', 200);
            $table->nullableTimestamps();

            $table->unique(['properties_id', 'locale']);
            
            $table->foreign('properties_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->set_schema_table);
    }

}
