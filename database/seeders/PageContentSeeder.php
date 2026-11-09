<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $site  = setting('site_name', 'ShopSarkar');
        $email = setting('contact_email', 'shopsarkar@gmail.com');
        $phone = setting('contact_phone', '+92 331 5146546');

        $privacy = <<<HTML
<p>At {$site}, your privacy matters to us. This Privacy Policy explains what information we collect, how we use it, and the choices you have when you shop with us.</p>
<h2>Information We Collect</h2>
<ul>
<li><strong>Personal details:</strong> your name, phone number, email address and delivery address when you place an order or create an account.</li>
<li><strong>Order information:</strong> the products you buy, order value and payment method (Cash on Delivery).</li>
<li><strong>Technical data:</strong> basic details such as your device and browser, used to keep our website working smoothly.</li>
</ul>
<h2>How We Use Your Information</h2>
<ul>
<li>To process, confirm and deliver your orders.</li>
<li>To contact you about your order or answer your questions.</li>
<li>To improve our products, website and overall customer experience.</li>
<li>To send you offers and updates — only if you choose to receive them.</li>
</ul>
<h2>Sharing Your Information</h2>
<p>We never sell your personal information. We only share the details needed to deliver your order (for example, your name, address and phone number with our courier partners).</p>
<h2>Data Security</h2>
<p>We take reasonable measures to protect your personal information and use it only for the purposes described in this policy.</p>
<h2>Your Choices</h2>
<p>You can update your account details at any time, or contact us to request a change to your information.</p>
<h2>Contact Us</h2>
<p>If you have any questions about this Privacy Policy, email us at <strong>{$email}</strong> or call <strong>{$phone}</strong>.</p>
HTML;

        $terms = <<<HTML
<p>Welcome to {$site}. By accessing our website and placing an order, you agree to the following Terms &amp; Conditions. Please read them carefully.</p>
<h2>Products &amp; Pricing</h2>
<ul>
<li>All prices are listed in Pakistani Rupees (Rs.) and include applicable charges unless stated otherwise.</li>
<li>We try to display accurate product details, colours and descriptions, but slight variations may occur.</li>
<li>Prices and offers may change at any time without prior notice.</li>
</ul>
<h2>Orders</h2>
<ul>
<li>Placing an order means you are making an offer to buy the selected product(s).</li>
<li>We reserve the right to accept or cancel any order, for example if a product is out of stock or the details provided are incorrect.</li>
<li>Please make sure your name, address and phone number are correct so we can deliver without delay.</li>
</ul>
<h2>Payment</h2>
<p>We currently offer <strong>Cash on Delivery (COD)</strong>. You pay the courier in cash at the time your order is delivered.</p>
<h2>Delivery</h2>
<p>We deliver across Pakistan. Delivery times may vary depending on your location and courier availability. See our Shipping Policy for details.</p>
<h2>Intellectual Property</h2>
<p>All content on this website — including the {$site} name, logo, text and images — belongs to {$site} and may not be used without permission.</p>
<h2>Limitation of Liability</h2>
<p>{$site} is not responsible for any indirect or incidental loss arising from the use of our website or products, to the extent permitted by law.</p>
<h2>Contact Us</h2>
<p>For any questions about these Terms, email <strong>{$email}</strong> or call <strong>{$phone}</strong>.</p>
HTML;

        $returns = <<<HTML
<p>We want you to love your purchase. If something is not right, our Return &amp; Refund Policy is here to help.</p>
<h2>Return Window</h2>
<p>You may request a return within <strong>7 days</strong> of receiving your order, provided the item meets the conditions below.</p>
<h2>Conditions for Return</h2>
<ul>
<li>The product must be unused, unopened and in its original packaging.</li>
<li>For fragrances, the seal must be intact — opened or used perfumes cannot be returned for hygiene reasons.</li>
<li>Proof of purchase (order number) is required.</li>
</ul>
<h2>Damaged or Wrong Items</h2>
<p>If you receive a damaged, defective or incorrect product, please contact us within <strong>48 hours</strong> of delivery with a photo, and we will arrange a replacement or refund at no extra cost.</p>
<h2>How to Request a Return</h2>
<ul>
<li>Contact us at <strong>{$email}</strong> or <strong>{$phone}</strong> with your order number and reason.</li>
<li>Our team will guide you through the next steps.</li>
</ul>
<h2>Refunds</h2>
<p>Once your return is received and approved, your refund will be processed. For Cash on Delivery orders, refunds are made via EasyPaisa, JazzCash or bank transfer to your provided account.</p>
<h2>Exchanges</h2>
<p>Prefer a different product? We are happy to arrange an exchange, subject to availability.</p>
HTML;

        $shipping = <<<HTML
<p>We deliver premium fragrances right to your doorstep across Pakistan. Here is everything you need to know about our shipping.</p>
<h2>Delivery Areas</h2>
<p>We offer <strong>nationwide delivery</strong> across all major cities and towns in Pakistan.</p>
<h2>Delivery Time</h2>
<ul>
<li><strong>Major cities:</strong> usually 2–4 working days.</li>
<li><strong>Other areas:</strong> usually 3–6 working days.</li>
<li>Orders are processed within 24–48 hours after confirmation.</li>
</ul>
<h2>Shipping Charges</h2>
<p>Shipping charges (if any) are shown clearly at checkout before you confirm your order. Keep an eye out for free-shipping offers on qualifying orders.</p>
<h2>Cash on Delivery</h2>
<p>Pay conveniently in cash when your order arrives. Please keep the exact amount ready for the courier.</p>
<h2>Order Tracking</h2>
<p>Once your order is dispatched, our team will share tracking details so you can follow your parcel.</p>
<h2>Delays</h2>
<p>Occasionally, delivery may be delayed due to weather, public holidays or courier issues. We appreciate your patience and will keep you informed.</p>
<h2>Need Help?</h2>
<p>For any shipping questions, contact us at <strong>{$email}</strong> or <strong>{$phone}</strong>.</p>
HTML;

        $map = [
            'privacy-policy' => $privacy,
            'terms-and-conditions' => $terms,
            'return-refund-policy' => $returns,
            'shipping-policy' => $shipping,
        ];

        foreach ($map as $slug => $html) {
            Page::where('slug', $slug)->update(['content' => $html]);
        }
    }
}
