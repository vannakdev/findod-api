<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyForiegnKeyToFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('favorites')->truncate();

        Schema::table('favorites', function (Blueprint $table) {
            $table->integer('properties_id')->unsigned()->change();
            $table->integer('users_id')->unsigned()->change();

            $table->foreign('properties_id')
                ->references('id')->on('properties')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('users_id')
                ->references('id')->on('users')
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
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['properties_id']);
            $table->dropForeign(['users_id']);

        });
    }
}
