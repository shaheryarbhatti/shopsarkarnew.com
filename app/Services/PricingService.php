<?php

namespace App\Services;

class PricingService
{
    /**
     * Compute order totals from a subtotal using store settings.
     *
     * @return array{subtotal:float,shipping:float,tax:float,total:float,free_shipping:bool,shipping_enabled:bool,tax_enabled:bool,tax_label:string,tax_rate:float}
     */
    public function totals(float $subtotal): array
    {
        $shippingEnabled = setting_bool('shipping_enabled', true);
        $taxEnabled      = setting_bool('tax_enabled', false);

        // Shipping
        $shipping = 0.0;
        $freeShipping = false;
        if ($shippingEnabled && $subtotal > 0) {
            $shipping = (float) setting('shipping_flat_rate', 0);

            if (setting_bool('free_shipping_enabled', false)) {
                $threshold = (float) setting('free_shipping_threshold', 0);
                if ($threshold > 0 && $subtotal >= $threshold) {
                    $shipping = 0.0;
                    $freeShipping = true;
                }
            }
        }

        // Tax (percentage of subtotal)
        $taxRate = (float) setting('tax_rate', 0);
        $tax = 0.0;
        if ($taxEnabled && $taxRate > 0) {
            $tax = round($subtotal * ($taxRate / 100), 2);
        }

        $total = $subtotal + $shipping + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'shipping' => round($shipping, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
            'free_shipping' => $freeShipping,
            'shipping_enabled' => $shippingEnabled,
            'tax_enabled' => $taxEnabled && $taxRate > 0,
            'tax_label' => (string) setting('tax_label', 'Tax'),
            'tax_rate' => $taxRate,
        ];
    }
}
