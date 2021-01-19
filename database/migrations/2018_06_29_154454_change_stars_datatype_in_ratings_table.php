<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStarsDatatypeInRatingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('ratings', function (Blueprint $table) {
            $table->float('stars', 8, 1)->unsigned()->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('ratings', function (Blueprint $table) {
            $table->integer('stars')->unsigned()->change();
        });
    }

}
