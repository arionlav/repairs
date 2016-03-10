<?php
namespace App\Http\Controllers;

use App\Http\Models\SitemapModel;
use Response;

/**
 * Class SitemapController is responsible for creating sitemap.xml file
 *
 * @package App\Http\Controllers
 */
class SitemapController extends Controller
{
    /**
     * Set model class in $this->model variable
     */
    public function __construct()
    {
        $this->model = new SitemapModel();
    }

    /**
     * Get sitemap .xlsx file
     *
     * @return \Illuminate\Http\Response
     */
    public function getSitemap()
    {
        $this->model->getIndexPage();
        $this->model->getCategoryPages();
        $this->model->getPosts();

        return Response::view('sitemap.sitemap', ['urlsAll' => $this->model->urlsAll])
            ->header('Content-Type', 'application/xml');
    }
}
