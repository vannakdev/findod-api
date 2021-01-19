<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTypesTable extends Migration {

    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'device_types';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable($this->set_schema_table))
            return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('index_number')->nullable(FALSE);
            $table->string('platform', 30)->nullable(FALSE);
        });

        // Insert some device type
        DB::table($this->set_schema_table)->insert([
                ['index_number' => '0', 'platform' => "iOS"], ['index_number' => '1', 'platform' => "ANDROID"], ['index_number' => '2', 'platform' => "AMAZON"], ['index_number' => '3', 'platform' => "WINDOWSPHONE (MPNS)"], ['index_number' => '4', 'platform' => "CHROME APPS / EXTENSIONS"], ['index_number' => '5', 'platform' => "CHROME WEB PUSH"], ['index_number' => '6', 'platform' => "WINDOWS (WNS)"], ['index_number' => '7', 'platform' => "SAFARI"], ['index_number' => '8', 'platform' => "FIREFOX"], ['index_number' => '9', 'platform' => "MACOS"], ['index_number' => '10', 'platform' => "ALEXA"], ['index_number' => '11', 'platform' => "EMAIL"]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->set_schema_table);
    }

}
