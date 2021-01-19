<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnLanguageIdToLocaleInNotificationManagerContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\NotificationManagerContents::query()->delete();
        Schema::table('notification_manager_contents', function (Blueprint $table) {
            $table->dropColumn('language_id')->change();
            $table->char('locale', 20)->after('title')->index();
            $table->dropUnique('notification_manager_id_language_id');
            $table->unique(['notification_manager_id', 'locale'], 'notification_manager_id_locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_manager_contents', function (Blueprint $table) {
            // $table->unsignedInteger('language_id');
            // $table->dropColumn('locale')->change();
            // $table->dropUnique('notification_manager_id_locale');
            // $table->unique(["notification_manager_id", "language_id"],'notification_manager_id_language_id');
        });
    }
}
