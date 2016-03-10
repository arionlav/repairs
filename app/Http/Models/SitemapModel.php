<?php
namespace App\Http\Models;

use DB;

/**
 * Class SitemapModel provide logic for collection all links
 *
 * @package App\Http\Models
 */
class SitemapModel
{
    /**
     * @var array Array with all <url>'s in our site
     */
    public $urlsAll = [];

    /**
     * Add in array $this->urlsAll all articles from database
     */
    public function getPosts()
    {
        $allPosts = DB::table('posts')
            ->get();

        if (! empty($allPosts)) {
            foreach ($allPosts as $post) {
                $lastModDb = DB::table('posts')
                    ->where('id', $post->id)
                    ->value('date');
                $lastMod   = $this->createLastMod($lastModDb);

                $this->urlsAll['posts'][$post->id]['url']      = GeneralModel::getPostUrl($post->id, $post->header);
                $this->urlsAll['posts'][$post->id]['lastMod']  = $lastMod;
                $this->urlsAll['posts'][$post->id]['change']   = 'daily';
                $this->urlsAll['posts'][$post->id]['priority'] = 1;
            }
        }
    }

    /**
     * Add in array $this->urlsAll index page
     */
    public function getIndexPage()
    {
        $lastModDb = DB::table('posts')
            ->max('date');
        $lastMod   = $this->createLastMod($lastModDb);

        $this->urlsAll['static'][0]['url']      = url('/');
        $this->urlsAll['static'][0]['lastMod']  = $lastMod;
        $this->urlsAll['static'][0]['change']   = 'daily';
        $this->urlsAll['static'][0]['priority'] = 0.8;
    }

    /**
     * Add in array $this->urlsAll all categories from database
     */
    public function getCategoryPages()
    {
        $allCategories = DB::table('category')
            ->select('id', 'name')
            ->get();

        foreach ($allCategories as $category) {
            $lastModDb = DB::table('posts')
                ->where('category', $category->id)
                ->max('date');
            $lastMod   = $this->createLastMod($lastModDb);

            $url = url('category' . $category->id . '/' . strtolower(GeneralModel::translit($category->name)));

            $this->urlsAll['category'][$category->id]['url']      = $url;
            $this->urlsAll['category'][$category->id]['lastMod']  = $lastMod;
            $this->urlsAll['category'][$category->id]['change']   = 'daily';
            $this->urlsAll['category'][$category->id]['priority'] = 0.8;
        }
    }

    /**
     * Create date in right format for <lastmod> sequence
     *
     * @param $lastModDb
     * @return bool|string
     */
    protected function createLastMod($lastModDb)
    {
        return date('Y-m-d', strtotime($lastModDb));
    }
}
