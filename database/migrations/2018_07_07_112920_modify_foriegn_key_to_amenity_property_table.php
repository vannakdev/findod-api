<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyForiegnKeyToAmenityPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('amenity_property')->truncate();

        Schema::table('amenity_property', function (Blueprint $table) {
            $table->integer('property_id')->unsigned()->change();
            $table->integer('amenities_id')->unsigned()->change();

            $table->foreign('property_id')
                ->references('id')->on('properties')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('amenities_id')
                ->references('id')->on('amenities')
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
        Schema::table('amenity_property', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['amenities_id']);


        });
    }
}
