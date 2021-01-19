<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'properties';

    /**
     * Run the migrations.
     * @table properties
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) {
            return;
        }
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('pro_title', 150)->nullable()->default('');
            $table->string('pro_public_id', 20)->nullable()->default('');
            $table->float('pro_price')->nullable()->default('0');
            $table->integer('pro_currency')->nullable()->default(1);
            $table->string('pro_land_mark', 50)->nullable()->default('0.00');
            $table->integer('pro_floor')->nullable()->default('0');
            $table->float('pro_square_feet')->nullable()->default('0');
            $table->integer('pro_bed_rooms')->nullable()->default('0');
            $table->integer('pro_bath_rooms')->nullable()->default('0');
            $table->tinyInteger('pro_parking')->nullable()->default('0');
            $table->string('pro_detail')->nullable()->default('');
            $table->integer('pro_age')->nullable()->default('0');
            $table->string('pro_city', 50)->nullable()->default('');
            $table->string('pro_state', 50)->nullable()->default('');
            $table->string('pro_zip', 10)->nullable()->default('0');
            $table->string('pro_address')->nullable()->default('');
            $table->integer('pro_search_type')->nullable()->default(1)->comment('property for Sale, Rent...');
            $table->char('pro_amenities', 50)->nullable()->default(null);
            $table->float('pro_lng')->nullable()->default(null);
            $table->float('pro_lat')->nullable()->default(null);
            $table->integer('pro_residence')->nullable()->default(0)->comment('Type of property: Condo, Vila,...');
            $table->text('pro_photos');
            $table->string('pro_videos', 50)->nullable()->default('');
            $table->text('pro_plan')->nullable()->default(null);
            $table->char('pro_status', 2)->nullable()->default('1')->comment('New project, Resale Properties');
            $table->string('pro_contact_name', 50)->nullable()->default('');
            $table->integer('pro_user_view_point')->nullable()->default('0');
            $table->string('pro_contact_email', 50)->nullable()->default('');
            $table->string('pro_contact_number', 50)->nullable()->default('');
            $table->integer('pro_use_id');
            $table->tinyInteger('pro_active')->nullable()->default(1);
            $table->float('pro_rating')->nullable()->default('0.0');
            $table->integer('pro_view_counter')->nullable()->default('0');
            $table->float('pro_unite_price')->nullable()->default('0.0');
            $table->integer('pro_month')->nullable()->default('0')->comment('period of contract of rent');
            $table->float('pro_square_price')->nullable()->default('0');
            $table->string('pro_thumbnail', 100)->nullable()->default(null);

            $table->index(['pro_search_type'], 'FK_properties_property_type');

            $table->index(['pro_currency'], 'FK_properties_currency');

            $table->index(['pro_use_id'], 'FK_properties_users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->set_schema_table);
    }
}
