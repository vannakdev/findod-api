<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //To sovle the foreign Key when perform the truncate operation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //End To sovle the foreign Key when perform the truncate operation
        $this->call([
            UserRolesTableSeeder::class,
            UsersTableSeeder::class,
                       AmenitiesTableSeeder::class,
                       CurrencyTableSeeder::class,
                       ResidencesTableSeeder::class,
                       ResidenceTranslationTableSeeder::class,
                       // YoutubeAccessTokenTableSeeder::class,
                       SocailTableSeeder::class,
                       PropertyTypeTableSeeder::class,
            NotificationManagerTableSeeder::class,
            NotificationManagerContentsTableSeeder::class,
            StatisticOfPirceRangesTableSeeder::class,
            // ApplicatoinSettingTableSeeder::class,
                       AgeCategoryTableSeeder::class,
                       AgeCategoryTranslationTableSeeder::class,
            PropertyStatusTableSeeder::class,
            PropertyStatusTranslationsTableSeeder::class,
            ResidenceTypeTableSeeder::class,
            ResidenceTypeTranslationTableSeeder::class,
            // ProtectedArticleTableSeeder::class,
            PropertyTypeTranslationsTableSeeder::class,
            AmenityTranslationsTableSeeder::class,
            FloorCategory::class,
            FloorCategoryTranslations::class,
            ProjectNameTableSeeder::class,
            ProjectNameTranslationsTableSeeder::class,
            // AdminSettingTableSeeder::class,
            AdvertisementTableSeeder::class,
        ]);
        //To sovle the foreign Key when perform the truncate operation
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //End To sovle the foreign Key when perform the truncate operation
    }
}
