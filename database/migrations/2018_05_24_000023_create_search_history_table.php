<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchHistoryTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'search_history';

    /**
     * Run the migrations.
     * @table search_history
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
            $table->integer('user_id')->nullable()->default('0');
            $table->text('request_result')->nullable()->default(null);
            $table->text('request_query')->nullable()->default(null);

            $table->index(['user_id'], 'FK_search_history_users');
            $table->softDeletes();
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
        Schema::dropIfExists($this->set_schema_table);
    }
}
