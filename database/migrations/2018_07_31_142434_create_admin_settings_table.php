<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('site_name', 255)->nullable(false)->default('');
            $table->string('meta_keyword', 255)->nullable(false)->default('');
            $table->string('meta_description', 255)->nullable(false)->default('');
            $table->string('site_logo', 255)->nullable(false)->default('');
            $table->string('site_fav_icon', 255)->nullable(false)->default('');
            $table->string('default_language', 32)->nullable(false)->default('');
            $table->string('default_currency', 32)->nullable(false)->default('');
            $table->string('aboutus', 255)->nullable(false)->default('');
            $table->string('site_address', 255)->nullable(false)->default('');
            $table->string('phone_number', 32)->nullable(false)->default('');
            $table->string('email_id', 32)->nullable(false)->default('');
            $table->string('facebook_link', 255)->nullable(false)->default('');
            $table->string('twitter_link', 255)->nullable(false)->default('');
            $table->string('linkedid_link', 255)->nullable(false)->default('');
            $table->string('google_link', 255)->nullable(false)->default('');
            $table->string('apple_store', 255)->nullable(false)->default('');
            $table->string('play_store', 255)->nullable(false)->default('');
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
        Schema::dropIfExists('admin_settings');
    }
}
