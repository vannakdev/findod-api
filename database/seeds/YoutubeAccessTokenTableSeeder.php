<?php

use Illuminate\Database\Seeder;

class YoutubeAccessTokenTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('youtube_access_tokens')->truncate();
        DB::table('youtube_access_tokens')->insert([
            'access_token' => '{\"access_token\":\"ya29.GlunBV3wPCckUL_RxiBHe_PUlefeg5iGcwn_9rLRoMSvJOuCxsL5btBmihGOCwpOecuoG1TATOG0tnPFvxx65Cpm6Rq2EjHun5S764vudMi_xmWDLnHorQGONBer\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"refresh_token\":\"1\\/gzgQT5T1mkP5x970RYjXBf2LrnqDxSnRczgVFugjEO4\",\"created\":1524567028}',
            'refresh_token' => '\"1\\/gzgQT5T1mkP5x970RYjXBf2LrnqDxSnRczgVFugjEO4\"']);
    }

}
