<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class GenerateSiteMapJob implements ShouldQueue
{
    use Queueable;

    const SITEMAP_XML = 'sitemap.xml';
    const USERS_SITEMAP_XML = 'users-sitemap.xml';
    const CATEGORIES_SITEMAP_XML = 'categories-sitemap.xml';
    const POSTS_SITEMAP_XML = 'posts-sitemap.xml';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mainSiteMap = Sitemap::create();
        $usersSiteMap = Sitemap::create();
        $categoriesSiteMap = Sitemap::create();
        $postsSiteMap = Sitemap::create();

        $mainSiteMap->add(Url::create(route("admin.dashboard"))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $mainSiteMap->add(Url::create(route('login'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        $mainSiteMap->add(Url::create(route("admin.users.index"))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        User::chunk(100, function ($users) use ($mainSiteMap, $usersSiteMap) {
            foreach ($users as $user){
                $usersSiteMap->add(Url::create(route("admin.users.show", $user->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
                $mainSiteMap->add(Url::create(route("admin.users.show", $user->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            }
        });

        $mainSiteMap->add(Url::create(route("admin.categories.index"))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        Category::chunk(100, function ($categories) use ($mainSiteMap, $categoriesSiteMap) {
            foreach ($categories as $category){
                $categoriesSiteMap->add(Url::create(route("admin.categories.show", $category->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
                $mainSiteMap->add(Url::create(route("admin.categories.show", $category->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            }
        });

        $mainSiteMap->add(Url::create(route("admin.posts.index"))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        Post::chunk(100, function ($posts) use ($mainSiteMap, $postsSiteMap) {
            foreach ($posts as $post){
                $postsSiteMap->add(Url::create(route("admin.posts.show", $post->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
                $mainSiteMap->add(Url::create(route("admin.posts.show", $post->id))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            }
        });

        $mainSiteMap->writeToFile(public_path(self::SITEMAP_XML));
        $usersSiteMap->writeToFile(public_path(self::USERS_SITEMAP_XML));
        $categoriesSiteMap->writeToFile(public_path(self::CATEGORIES_SITEMAP_XML));
        $postsSiteMap->writeToFile(public_path(self::POSTS_SITEMAP_XML));
    }
}
