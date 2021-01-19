<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendingTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'trending';

    /**
     * Run the migrations.
     * @table trending
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
            $table->integer('tre_pro_id')->nullable()->default(null);
            $table->integer('tre_counter')->nullable()->default(null);
            $table->date('tre_date')->nullable()->default(null);
            $table->char('status', 2)->nullable()->default('1');

            $table->unique(['tre_pro_id', 'tre_date'], 'tre_pro_id_tre_date');
            $table->nullableTimestamps();
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
