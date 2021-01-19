<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyContentFieldInChatMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //using Raw SQL due to the doctrin not support Table contain Enum Field
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN content varchar(1000) NOT NULL DEFAULT ''");
        // Schema::table('chat_messages', function (Blueprint $table) {
        //     $table->string('content', 1000)->change();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //using Raw SQL due to the doctrin not support Table contain Enum Field
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN content varchar(200) NOT NULL DEFAULT ''");
    }
}
