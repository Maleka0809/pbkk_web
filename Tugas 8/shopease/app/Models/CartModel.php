<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table            = 'carts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getOrCreateCart(int $userId): array
    {
        $cart = $this->where('user_id', $userId)->first();
        if (!$cart) {
            $cartId = $this->insert(['user_id' => $userId]);
            $cart = $this->find($cartId);
        }
        return $cart;
    }
}

