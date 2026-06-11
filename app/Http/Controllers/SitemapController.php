<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = SeoService::generateSitemap();
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}