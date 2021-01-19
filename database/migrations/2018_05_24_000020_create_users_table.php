<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable($this->set_schema_table))
            return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name', 50)->nullable()->default('');
            $table->string('last_name', 50)->nullable()->default('');
            $table->string('username', 32)->nullable()->default('');
            $table->string('photo', 200)->nullable()->default('');
            $table->string('email');
            $table->integer('provider_id')->nullable()->default(null);
            $table->string('password', 64);
            $table->string('api_token')->nullable()->default('');
            $table->string('playerId')->default(0);
            $table->integer('userol_id')->default('2');
            $table->char('phone', 50)->nullable()->default('');
            $table->char('gender', 5)->nullable()->default('');
            $table->timestamp('dob')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->char('country_code', 5)->nullable()->default('855');
            $table->integer('status')->nullable()->default('1');
            $table->tinyInteger('active')->nullable()->default('1');
            $table->string('company_licence', 200)->nullable()->default('');
            $table->string('company_name', 50)->nullable()->default('');
            $table->string('company_number', 50)->nullable()->default('');
            $table->string('company_address', 50)->nullable()->default('');
            $table->integer('favorite_counter')->nullable()->default('0');
            $table->text('setting');

            $table->index(["userol_id"], 'fk_users_user_role_idx');

            $table->index(["provider_id"], 'FK_users_social');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists($this->set_schema_table);
        Schema::enableForeignKeyConstraints();
        // Schema::dropIfExists($this->set_schema_table);
    }

}
