<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyReportsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'property_reports';
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
            $table->increments('id');
            $table->integer('property_id')->unsigned()->nullable(FALSE);
            $table->integer('user_id')->unsigned()->nullable(FALSE);
            $table->integer('type_of_property_report_id')->unsigned()->nullable(FALSE)->default(1);
            $table->string('comment')->nullable(FALSE)->default('');
            $table->timestamps();
            $table->unique(['property_id', 'user_id']);

            $table->index(["property_id"], 'FK_report_property_properties');
            $table->index(["user_id"], 'FK_report_property_user');
            $table->index(["type_of_property_report_id"], 'FK_type_of_property_report');            


        });
        Schema::table($this->set_schema_table, function (Blueprint $table) {
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('type_of_property_report_id')->references('id')->on('type_of_property_reports')->onDelete('cascade');
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
