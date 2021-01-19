<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToSlugOfPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('ALTER TABLE posts ENGINE = InnoDB');
        // Schema::table('posts', function (Blueprint $table) {
        //     $table->engine = 'InnoDB';
        //     $table->unique('slug');
        //     // $table->dropForeign('chat_channels_property_id_foreign');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_unique');
        });
    }
}
