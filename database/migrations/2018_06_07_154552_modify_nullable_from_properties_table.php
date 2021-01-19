<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNullableFromPropertiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('pro_title', 150)->nullable(FALSE)->default('')->change();
            $table->string('pro_public_id', 20)->nullable(FALSE)->default('')->change();
            $table->integer('pro_price')->nullable(FALSE)->default('0')->change();
            $table->integer('pro_currency')->nullable(FALSE)->default(1)->change();
            $table->string('pro_land_mark', 50)->nullable(FALSE)->default('0.00')->change();
            $table->integer('pro_floor')->nullable(FALSE)->default('0')->change();
            $table->float('pro_square_feet')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->integer('pro_bed_rooms')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->integer('pro_bath_rooms')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->boolean('pro_parking')->nullable(FALSE)->default('1')->change();
            $table->string('pro_detail')->nullable(FALSE)->default('')->change();
            $table->integer('pro_age')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->string('pro_city', 50)->nullable(FALSE)->default('')->change();
            $table->string('pro_state', 50)->nullable(FALSE)->default('')->change();
            $table->string('pro_zip', 10)->nullable(FALSE)->default('0')->change();
            $table->string('pro_address')->nullable(FALSE)->default('')->change();
            $table->dropColumn('pro_amenities');
            $table->integer('pro_search_type')->unsigned()->nullable(FALSE)->default(1)->comment('property for Sale, Rent...')->change();
            $table->float('pro_lng')->nullable(FALSE)->change();
            $table->float('pro_lat')->nullable(FALSE)->change();
            $table->integer('pro_residence')->nullable(FALSE)->default(0)->comment('Type of property: Condo, Vila,...')->change();
            $table->json('pro_photos')->change();
            $table->string('pro_videos', 50)->nullable(FALSE)->default('')->change();
            $table->json('pro_plan')->nullable()->default(null)->change();
            $table->integer('pro_status')->nullable(FALSE)->default(1)->comment('New project, Resale Properties')->change();
            $table->string('pro_contact_name', 50)->nullable(FALSE)->default('')->change();
            $table->integer('pro_user_view_point')->nullable(FALSE)->default('0')->change();
            $table->string('pro_contact_email', 50)->nullable(FALSE)->default('')->change();
            $table->string('pro_contact_number', 50)->nullable(FALSE)->default('')->change();
            $table->integer('pro_use_id')->unsigned()->nullable(FALSE)->change();
            $table->boolean('pro_active')->nullable(FALSE)->default('1')->change();
            $table->float('pro_rating')->nullable(FALSE)->default('0.0')->change();
            $table->integer('pro_view_counter')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->integer('pro_unite_price')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->integer('pro_month')->unsigned()->nullable(FALSE)->default('0')->comment('period of contract of rent')->change();
            $table->float('pro_square_price')->unsigned()->nullable(FALSE)->default('0')->change();
            $table->string('pro_thumbnail', 100)->nullable(FALSE)->default('')->change();
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
            // $table->string('pro_public_id', 20)->nullable()->default('')->change();
            // $table->integer('pro_price')->nullable()->default('0')->change();
            // $table->integer('pro_currency')->nullable()->default(1)->change();
            // $table->string('pro_land_mark', 50)->nullable()->default('0.00')->change();
            // $table->integer('pro_floor')->nullable()->default('0')->change();
            // $table->float('pro_square_feet')->nullable()->default('0')->change();
            // $table->integer('pro_bed_rooms')->nullable()->default('0')->change();
            // $table->integer('pro_bath_rooms')->nullable()->default('0')->change();
            // $table->boolean('pro_parking')->nullable()->default('0')->change();
            // $table->string('pro_detail')->nullable()->default('')->change();
            // $table->integer('pro_age')->nullable()->default('0')->change();
            // $table->string('pro_city', 50)->nullable()->default('')->change();
            // $table->string('pro_state', 50)->nullable()->default('')->change();
            // $table->string('pro_zip', 10)->nullable()->default('0')->change();
            // $table->string('pro_address')->nullable()->default('')->change();
            // $table->integer('pro_search_type')->nullable()->default(1)->comment('property for Sale, Rent...')->change();
            // $table->float('pro_lng')->nullable()->default(null)->change();
            // $table->float('pro_lat')->nullable()->default(null)->change();
            // $table->integer('pro_residence')->nullable()->default(0)->comment('Type of property: Condo, Vila,...')->change();
            // $table->json('pro_photos')->change();
            // $table->string('pro_videos', 50)->nullable()->default('')->change();
            // $table->json('pro_plan')->nullable()->default(null)->change();
            // $table->boolean('pro_status')->nullable()->default('1')->comment('New project, Resale Properties')->change();
            // $table->string('pro_contact_name', 50)->nullable()->default('')->change();
            // $table->integer('pro_user_view_point')->nullable()->default('0')->change();
            // $table->string('pro_contact_email', 50)->nullable()->default('')->change();
            // $table->string('pro_contact_number', 50)->nullable()->default('')->change();
            // $table->integer('pro_use_id')->change();
            // $table->boolean('pro_active')->nullable()->default(1)->change();
            // $table->float('pro_rating')->nullable()->default('0.0')->change();
            // $table->integer('pro_view_counter')->nullable()->default('0')->change();
            // $table->float('pro_unite_price')->nullable()->default('0.0')->change();
            // $table->integer('pro_month')->nullable()->default('0')->comment('period of contract of rent')->change();
            // $table->float('pro_square_price')->nullable()->default('0')->change();
            // $table->string('pro_thumbnail', 100)->nullable()->default(null)->change();
        });
    }

}
