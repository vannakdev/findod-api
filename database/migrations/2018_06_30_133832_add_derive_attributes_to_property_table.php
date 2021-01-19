<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeriveAttributesToPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('favorites_count')->unsigned()->nullable()->default(0);
            $table->integer('request_viewing_count')->unsigned()->nullable()->default(0);
            $table->integer('comment_count')->unsigned()->nullable()->default(0); //user rating and review counter
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['favorites_count', 'comment_count']);
        });
    }
}
