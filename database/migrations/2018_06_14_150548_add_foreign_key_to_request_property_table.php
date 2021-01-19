<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToRequestPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('request_property', function (Blueprint $table) {
            $table->integer('property_id')->unsigned()->change();
             $table->integer('users_id')->unsigned()->change();
             
             $table->foreign('property_id')
                    ->references('id')->on('properties')
                    ->onDelete('cascade');
            $table->foreign('users_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_property', function (Blueprint $table) {
        //    $table->dropForeign(['property_id', 'users_id']);
        });
    }
}
