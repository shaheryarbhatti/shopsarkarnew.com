<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected string $key = 'cart';

    /** Raw session data: [product_id => qty] */
    protected function raw(): array
    {
        return session()->get($this->key, []);
    }

    protected function save(array $data): void
    {
        session()->put($this->key, $data);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $data = $this->raw();
        $data[$productId] = ($data[$productId] ?? 0) + max(1, $qty);
        $this->save($data);
    }

    public function update(int $productId, int $qty): void
    {
        $data = $this->raw();
        if ($qty <= 0) {
            unset($data[$productId]);
        } else {
            $data[$productId] = $qty;
        }
        $this->save($data);
    }

    public function remove(int $productId): void
    {
        $data = $this->raw();
        unset($data[$productId]);
        $this->save($data);
    }

    public function clear(): void
    {
        session()->forget($this->key);
    }

    /** Count of distinct units in cart */
    public function count(): int
    {
        return array_sum($this->raw());
    }

    /** Line items with product models and computed subtotals */
    public function items(): Collection
    {
        $data = $this->raw();
        if (empty($data)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($data))->get()->keyBy('id');

        return collect($data)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(function ($qty, $id) use ($products) {
                $product = $products->get($id);
                $price = $product->final_price;
                return (object) [
                    'product' => $product,
                    'qty' => (int) $qty,
                    'price' => $price,
                    'subtotal' => $price * $qty,
                ];
            })->values();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
