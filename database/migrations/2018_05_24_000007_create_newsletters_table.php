<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'newsletters';

    /**
     * Run the migrations.
     * @table newsletters
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('ref_no', 32)->nullable()->default(null);
            $table->string('template_name')->nullable()->default(null);
            $table->string('subject')->nullable()->default(null);
            $table->enum('newsletter_type', ['email', 'sms', 'notification'])->nullable()->default('email');
            $table->char('status', 2)->default('1');
            $table->text('body_content');
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
