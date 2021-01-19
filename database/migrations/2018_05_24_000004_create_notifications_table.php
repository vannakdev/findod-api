<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'notifications';

    /**
     * Run the migrations.
     * @table notifications
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
            $table->integer('notification_type_id');
            $table->integer('notification_manager_id');
            $table->string('comments', 100)->nullable()->default(null);
            $table->integer('sender_id')->nullable()->default(null);
            $table->integer('user_id');
            $table->integer('properties_id')->nullable()->default(null);
            $table->tinyInteger('status')->default('0');

            $table->index(['properties_id'], 'notifications_properties_id_foreign');

            $table->index(['id'], 'notifications_id_index');

            $table->index(['user_id'], 'notifications_user_id_foreign');
            $table->softDeletes();
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
