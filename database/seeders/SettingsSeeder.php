<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General / branding
            ['site_name', 'SARKAR', 'general', 'text'],
            ['site_logo', null, 'general', 'file'],
            ['currency_symbol', 'Rs.', 'general', 'text'],
            ['contact_email', 'info@shop.com', 'general', 'text'],
            ['contact_phone', '', 'general', 'text'],
            ['footer_about', 'Discover timeless fragrances crafted for those who command presence. Luxury, delivered to your door.', 'general', 'textarea'],

            // Hero section (home page)
            ['hero_heading_en', "SARKAR: EMPIRE\nTHE SCENT OF\nETERNAL POWER.", 'hero', 'textarea'],
            ['hero_heading_ur', 'ریاستی شان اور شاندار وراثہ', 'hero', 'textarea'],
            ['hero_subtext_en', '', 'hero', 'textarea'],
            ['hero_subtext_ur', '', 'hero', 'textarea'],
            ['hero_button_text', 'Buy Now', 'hero', 'text'],
            ['hero_button_text_ur', 'ابھی خریدیں', 'hero', 'text'],
            ['hero_video', null, 'hero', 'file'],
            ['hero_poster', null, 'hero', 'file'],
            ['hero_product_id', null, 'hero', 'number'], // product the Buy Now points to

            // Shipping
            ['shipping_enabled', '1', 'shipping', 'boolean'],
            ['shipping_flat_rate', '250', 'shipping', 'number'],
            ['free_shipping_enabled', '1', 'shipping', 'boolean'],
            ['free_shipping_threshold', '5000', 'shipping', 'number'],

            // Tax
            ['tax_enabled', '0', 'tax', 'boolean'],
            ['tax_rate', '0', 'tax', 'number'], // percentage
            ['tax_label', 'Tax', 'tax', 'text'],

            // Affiliate / withdrawals
            ['affiliate_enabled', '1', 'affiliate', 'boolean'],
            ['withdraw_min_amount', '500', 'affiliate', 'number'],
            ['earning_maturity_days', '3', 'affiliate', 'number'],
            ['affiliate_terms', 'Earn commission on every sale made through your personal referral link.', 'affiliate', 'textarea'],
        ];

        foreach ($defaults as [$key, $value, $group, $type]) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group, 'type' => $type]
            );
        }
    }
}
