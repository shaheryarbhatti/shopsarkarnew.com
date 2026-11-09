<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $heroProductId = setting('hero_product_id');

        $heroProduct = $heroProductId
            ? Product::active()->find($heroProductId)
            : null;

        if (! $heroProduct) {
            $heroProduct = Product::active()->where('is_featured', true)->first()
                ?? Product::active()->latest()->first();
        }

        return view('frontend.home', [
            'heroProduct' => $heroProduct,
            'heroVideo'   => setting_file('hero_video'),
            'heroPoster'  => setting_file('hero_poster'),
        ]);
    }
}
