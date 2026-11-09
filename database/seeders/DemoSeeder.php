<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'fragrances'],
            ['name' => 'Fragrances', 'is_active' => true, 'sort_order' => 1]
        );

        $product = Product::firstOrCreate(
            ['slug' => 'sarkar-oud-royale'],
            [
                'category_id' => $category->id,
                'name' => 'Sarkar Oud Royale',
                'sku' => 'SARKAR-OUD-001',
                'short_description' => 'A regal oud fragrance for those who command presence. Pour Homme et Femme.',
                'description' => '<p>Sarkar Oud Royale opens with rich oud and saffron, settling into a warm base of amber, musk and sandalwood. A long-lasting, luxurious scent crafted for eternal power.</p><ul><li>Solitaire Oud Collection</li><li>Pour Homme et Femme</li><li>Long lasting projection</li></ul>',
                'price' => 7500,
                'discounted_price' => 5999,
                'contributor_price' => 600,
                'stock' => 100,
                'manage_stock' => true,
                'status' => 'active',
                'is_featured' => true,
            ]
        );

        // (Product images & videos are added from the admin panel.)

        // Point the hero Buy Now button to this product
        Setting::put('hero_product_id', (string) $product->id, 'hero', 'number');
    }
}
