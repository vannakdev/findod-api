<?php

use Illuminate\Database\Seeder;

class ResidencesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \App\Residence::query()->delete();
        DB::table('residences')->insert([
            ['id'=>1,'res_title' => 'Flat', 'res_amenities' => '1,2,3,100', 'icon' => 'flat.png','position'=>1,'residence_type_id'=>1],
            ['id'=>2,'res_title' => 'Factory', 'res_amenities' => '2,3', 'icon' => 'factory.png','position'=>1,'residence_type_id'=>1],
            ['id'=>3,'res_title' => 'Land', 'res_amenities' => '1,2,4,5,7,8', 'icon' => 'land.png','position'=>2,'residence_type_id'=>1],
            ['id'=>4,'res_title' => 'Office', 'res_amenities' => '1,2,3,100', 'icon' => 'office.png','position'=>2,'residence_type_id'=>1],
            ['id'=>5,'res_title' => 'Shop House', 'res_amenities' => '1,2,3,5,7,8,6', 'icon' => 'shop.png','position'=>2,'residence_type_id'=>1],
            ['id'=>6,'res_title' => 'Warehouse', 'res_amenities' => '1', 'icon' => 'warehouse.png','position'=>2,'residence_type_id'=>1],
            ['id'=>7,'res_title' => 'Apartment', 'res_amenities' => '1', 'icon' => 'apartment.png','position'=>4,'residence_type_id'=>1],
            ['id'=>8,'res_title' => 'Retail', 'res_amenities' => '1', 'icon' => 'retail.png','position'=>1,'residence_type_id'=>1],
            ['id'=>9,'res_title' => 'Restaurant', 'res_amenities' => '38', 'icon' => 'restaurant.png','position'=>5,'residence_type_id'=>1],
            ['id'=>10,'res_title' => 'Villa', 'res_amenities' => '2,5,4,7', 'icon' => 'villa.png','position'=>2,'residence_type_id'=>2],
            ['id'=>11,'res_title' => 'Condo ', 'res_amenities' => '38', 'icon' => 'condo.png','position'=>2,'residence_type_id'=>2],
            ['id'=>12,'res_title' => 'Single Family Home', 'res_amenities'=>'38', 'icon' => 'single-family-home.png','position'=>3,'residence_type_id'=>2],
            ['id'=>13,'res_title' => 'Apartment ', 'res_amenities' => '38', 'icon' => 'apartment.png','position'=>3,'residence_type_id'=>2],
            ['id'=>14,'res_title' => 'Flat', 'res_amenities' => '38,5', 'icon' => 'flat.png','position'=>3,'residence_type_id'=>2],
            ['id'=>15,'res_title' => 'Hotel ', 'res_amenities' => '38', 'icon' => 'hotel.png','position'=>2,'residence_type_id'=>2],
            ['id'=>16,'res_title' => 'Borey', 'res_amenities' => '38', 'icon' => 'borey.png','position'=>2,'residence_type_id'=>2],
            ['id'=>17,'res_title' => 'Land ', 'res_amenities' => '38', 'icon' => 'land.png','position'=>2,'residence_type_id'=>3],
        ]);
    }

}
