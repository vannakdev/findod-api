<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPropertyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('properties', function (Blueprint $table) {

            $table->integer('pro_residence')->unsigned()->nullable(FALSE)->default(0)->comment('Type of property: Condo, Vila,...')->change();
            $table->integer('pro_currency')->unsigned()->nullable(FALSE)->default(1)->change();

            $table->foreign('pro_residence')
                    ->references('id')->on('residences')
                    ->onDelete('cascade');

            $table->foreign('pro_search_type')
                    ->references('id')->on('property_type')
                    ->onDelete('cascade');

            $table->foreign('pro_currency')
                    ->references('id')->on('currency')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            // $table->dropForeign(['pro_residence', 'pro_search_type','pro_currency']);
        });
    }

}
