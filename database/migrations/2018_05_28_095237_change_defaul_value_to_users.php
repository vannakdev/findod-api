<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaulValueToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('provider_id')->notNullValu()->default(3)->comment('3= register by Find OD APP')->change();
            $table->unsignedInteger('userol_id')->notNullValu()->default(2)->comment('2--> sample user')->change();
            $table->string('api_token')->notNullValu()->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->integer('provider_id')->nullable()->default(null)->change();
            // $table->integer('userol_id')->default('2')->change();
            // $table->string('api_token')->nullable()->default('')->change();
        });
    }
}
