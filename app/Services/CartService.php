<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Session\Store as Session;

class CartService
{
    public function __construct(private Session $session) {}

    public function all(): array
    {
        return $this->session->get('cart', []);
    }

    public function add(Product $product, int $qty = 1): void
    {
        $cart = $this->all();
        $id   = $product->id;
        $cart[$id] = [
            'id'    => $id,
            'name'  => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'qty'   => ($cart[$id]['qty'] ?? 0) + max(1, $qty),
        ];
        $this->session->put('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        $this->session->put('cart', $cart);
    }

    public function updateQuantities(array $quantities): void
    {
        $cart = $this->all();
        foreach ($quantities as $id => $qty) {
            if (!isset($cart[$id])) continue;
            $qty = (int) $qty;
            if ($qty > 0) {
                $cart[$id]['qty'] = $qty;
            } else {
                unset($cart[$id]);
            }
        }
        $this->session->put('cart', $cart);
    }

    public function total(): float
    {
        return collect($this->all())->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function clear(): void
    {
        $this->session->forget('cart');
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }
}
