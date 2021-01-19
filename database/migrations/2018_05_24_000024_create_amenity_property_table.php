<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmenityPropertyTable extends Migration
{
    /**
     * Schema table name to migrate.
     * @var string
     */
    public $set_schema_table = 'amenity_property';

    /**
     * Run the migrations.
     * @table amenity_property
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
            $table->integer('property_id')->nullable()->default(null);
            $table->integer('amenities_id')->nullable()->default(null);

            $table->index(['amenities_id'], 'fk_amenity_property_amenities1_idx');

            $table->index(['property_id'], 'fk_amenity_property_properties1_idx');
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
