<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesTable extends Migration {

    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'residences';

    /**
     * Run the migrations.
     * @table residences
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable($this->set_schema_table))
            return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('res_title', 50)->default('0');
            $table->char('res_amenities', 50)->default('0');
            $table->string('icon')->nullable()->default('');
            $table->char('status', 2)->default('1');
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
