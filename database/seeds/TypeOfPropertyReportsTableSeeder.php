<?php

use Illuminate\Database\Seeder;

class TypeOfPropertyReportsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('type_of_property_reports')->truncate();
        DB::table('type_of_property_reports')->insert([
            [
                "id" => 1,
                "title" => "Any...",
                "content" => ""
            ],
            [
                "id" => 2,
                "title" => "Property already sold",
                "content" => "Buyer found the property on the site, after contact with the seller they confirm that the property ready sold."
            ],
            [
                "id" => 3,
                "title" => "Seller not responding/phone unreachable",
                "content" => "Buyers expect a response in what they consider reasonable time. Is it reasonable of your buyers to expect a response inside of 24 hours to at least say you will get back to them?"
            ],
            [
                "id" => 4,
                "title" => "Ads is duplicate",
                "content" => "Duplicate ads are two or more advertisements that have similar items/services, ad titles, ad descriptions, and/or photos posted by the same user."
            ],
            [
                "id" => 5,
                "title" => "Fraud reason",
                "content" => "Fraud reason is concerned with theory and practice of fraudulently representing online advertisement impressions, clicks, conversion or data events in order to generate revenue. While ad fraud is more generally associated with banner ads, video ads and in-app ads"
            ]

        ]);
    }

}
