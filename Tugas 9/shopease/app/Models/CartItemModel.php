<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table            = 'cart_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['cart_id', 'product_id', 'quantity'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getItemsByCart(int $cartId): array
    {
        return $this->select('cart_items.*, products.name as product_name, products.slug as product_slug, products.price, products.stock, products.image')
                    ->join('products', 'products.id = cart_items.product_id')
                    ->where('cart_items.cart_id', $cartId)
                    ->orderBy('cart_items.id', 'DESC')
                    ->findAll();
    }

    public function getCartTotals(int $cartId): array
    {
        $items = $this->getItemsByCart($cartId);
        $totalItems = 0;
        $totalPrice = 0.0;

        foreach ($items as $item) {
            $totalItems += (int) $item['quantity'];
            $totalPrice += ((float) $item['price'] * (int) $item['quantity']);
        }

        return [
            'items'       => $items,
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
        ];
    }
}

