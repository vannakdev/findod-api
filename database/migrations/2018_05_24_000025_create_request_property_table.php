<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPropertyTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'request_property';

    /**
     * Run the migrations.
     * @table request_property
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
            $table->integer('property_id')->nullable()->default(null);
            $table->integer('users_id')->nullable()->default(null);
            $table->string('description', 200)->nullable()->default(null);
            $table->index(['users_id'], 'FK_request_property_users');
            $table->index(['property_id'], 'FK_request_property_properties');
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
