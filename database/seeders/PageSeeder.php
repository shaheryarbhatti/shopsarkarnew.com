<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<p>We respect your privacy. This policy explains what information we collect, how we use it, and the choices you have. We only collect the data required to process your orders and improve your experience, and we never sell your personal information.</p>'
                    . '<h2>Cookies &amp; Tracking Technologies</h2>'
                    . '<p>We use cookies and similar technologies to operate the website, remember your cart and preferences, and understand how our store is used. You can control or delete cookies through your browser settings.</p>'
                    . '<p>We also use third-party advertising and analytics tools, including the <strong>Meta (Facebook) Pixel</strong> and the <strong>TikTok Pixel</strong>. These tools help us measure the performance of our ads and show you more relevant products. They may collect information such as the pages you view, items you add to your cart, and purchases you make. Where available, a hashed (encrypted) form of limited contact details such as your email or phone number may be shared with these platforms to match your activity for ad measurement &mdash; this information is encrypted before sharing and is not readable as plain text.</p>'
                    . '<p>This data is processed by Meta and TikTok under their own privacy policies. You can also opt out of personalised ads through your device or ad-platform settings.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<p>By using this website and placing an order you agree to our terms. All prices are in PKR. Orders are subject to availability and confirmation. Please read these terms carefully before making a purchase.</p>',
                'sort_order' => 2,
            ],
            [
                'title' => 'Return & Refund Policy',
                'slug' => 'return-refund-policy',
                'content' => '<p>If you are not satisfied with your purchase, you may request a return within 7 days of delivery. Items must be unused and in original packaging.</p>',
                'sort_order' => 3,
            ],
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'content' => '<p>We deliver nationwide across Pakistan. Orders are processed within 1–2 business days and typically delivered within 3–5 business days.</p>',
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
