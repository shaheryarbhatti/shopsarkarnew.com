<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Homepage
        $urls[] = ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'];

        // Affiliate page
        if (setting_bool('affiliate_enabled', true)) {
            $urls[] = ['loc' => route('earn'), 'priority' => '0.6', 'changefreq' => 'monthly'];
        }

        // Active products
        foreach (Product::active()->get() as $product) {
            $urls[] = [
                'loc' => route('product.show', $product->slug),
                'lastmod' => $product->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
            ];
        }

        // Published content pages
        foreach (Page::where('is_published', true)->get() as $page) {
            $urls[] = [
                'loc' => route('page.show', $page->slug),
                'lastmod' => $page->updated_at?->toAtomString(),
                'priority' => '0.4',
                'changefreq' => 'monthly',
            ];
        }

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
