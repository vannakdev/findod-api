<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectNameIdToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('project_name_id')->nullable()->unsigned()->index();

            $table->foreign('project_name_id')
                ->references('id')->on('project_name')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::disableForeignKeyConstraints();
        // Schema::table('properties', function (Blueprint $table) {
        //     $table->dropColumn('project_name_id');
        // });
        // Schema::enableForeignKeyConstraints();
    }
}
