<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaulValueToProperties extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('pro_title', 150)->default('Property Sale or Rent')->change();
            $table->float('pro_lat', 8, 2)->notNullValue()->default(0)->change();
            $table->float('pro_lng', 8, 2)->notNullValue()->default(0)->change();
            $table->string('pro_detail', 200)->notNullValue()->default("")->change();
            $table->string('pro_city', 100)->notNullValue()->default("")->change();
            $table->string('pro_state', 100)->notNullValue()->default("")->change();
            $table->string('pro_address')->notNullValue()->default("")->change();
            $table->unsignedInteger('pro_currency')->notNullable()->default(1)->change();
            $table->string('pro_videos')->notNullValue()->default("")->change();
            $table->string('pro_contact_name')->notNullValue()->default("")->change();
            $table->string('pro_contact_email')->notNullValue()->default("")->change();
            $table->string('pro_contact_number')->notNullValue()->default("")->change();
            $table->string('pro_thumbnail')->notNullValue()->default("")->change();
            $table->unsignedInteger('pro_residence')->notNullValu()->default(9)->change();
            $table->unsignedInteger('pro_use_id')->notNullValu()->default(2)->change();
            $table->integer('pro_search_type')->notNullValu()->default(4)->comment('property for Sale, Rent, Contract,All type...')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            // $table->string('pro_title', 150)->nullable()->default('')->change();
            // $table->float('pro_lng')->nullable()->default(null)->change();
            // $table->float('pro_lat')->nullable()->default(null)->change();
            // $table->string('pro_detail')->nullable()->default('')->change();
            // $table->string('pro_city', 50)->nullable()->default('')->change();
            // $table->string('pro_state', 50)->nullable()->default('')->change();
            // $table->string('pro_address')->nullable()->default('')->change();
            // $table->integer('pro_currency')->nullable()->default(1)->change();
            // $table->string('pro_videos', 50)->nullable()->default('')->change();
            // $table->string('pro_contact_name', 50)->nullable()->default('')->change();
            // $table->string('pro_contact_email', 50)->nullable()->default('')->change();
            // $table->string('pro_contact_number', 50)->nullable()->default('')->change();
            // $table->string('pro_thumbnail', 100)->nullable()->default(null)->change();
            // $table->integer('pro_residence')->nullable()->default(0)->comment('Type of property: Condo, Vila,...')->change();
            // $table->integer('pro_use_id')->change();
            // $table->integer('pro_search_type')->nullable()->default(1)->comment('property for Sale, Rent...')->change();
        });
    }

}
