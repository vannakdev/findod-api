<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('provider_id')
                    ->references('id')->on('social')
                    ->onDelete('cascade');
            $table->foreign('userol_id')
                    ->references('id')->on('user_role')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            // $table->dropForeign(['provider_id', 'userol_id']);
        });
    }

}
