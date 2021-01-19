<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenceTranslatesTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'residence_translates';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) {
            return;
        }
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('residence_id')->unsigned();
            $table->char('locale', 20)->index();
            $table->string('res_title', 200);
            $table->nullableTimestamps();

            $table->unique(['residence_id', 'locale']);
            $table->foreign('residence_id')->references('id')->on('residences')->onDelete('cascade');
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
