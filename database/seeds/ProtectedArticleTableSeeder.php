<?php

use Illuminate\Database\Seeder;

class ProtectedArticleTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_user_id = 1; //reference from UsersTableSeeder


        //default protected Blog
        if (!\App\Post::where('slug', 'privacy-policy')->first()) {
            $privacy = new \App\Post();
            $privacy->user_id = $admin_user_id;
            $privacy->title = "Privacy Policy";
            $privacy->slug = "privacy-policy";
            $privacy->content = "Privacy Policy";
            $privacy->visibility = "published";
            // $privacy->protected = true;
            $privacy->save();
        }

        if (!\App\Post::where('slug', 'term-of-use')->first()) {
            $term_of_use = new \App\Post();
            $term_of_use->user_id = $admin_user_id;
            $term_of_use->title = "Term of Use";
            $term_of_use->slug = "term-of-use";
            $term_of_use->content = "Term of Use";
            $term_of_use->visibility = "published";
            // $term_of_use->protected = true;
            $term_of_use->save();
        }

        if (!\App\Post::where("slug", 'about-us')->first()) {
            $about_us = new \App\Post();
            $about_us->user_id = $admin_user_id;
            $about_us->title = "About Us";
            $about_us->slug = "about-us";
            $about_us->content = "About Us";
            $about_us->visibility = "published";
            // $about_us->protected = true;
            $about_us->save();
        }
    }
}
