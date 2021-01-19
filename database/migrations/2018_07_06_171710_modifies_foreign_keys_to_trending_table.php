<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiesForeignKeysToTrendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('trending')->truncate();
        Schema::table('trending', function (Blueprint $table) {
            $table->integer('tre_pro_id')->unsigned()->change();

            $table->foreign('tre_pro_id')
                    ->references('id')->on('properties')
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
        Schema::table('trending', function (Blueprint $table) {
            $table->dropForeign(['tre_pro_id']);
        });
    }
}
