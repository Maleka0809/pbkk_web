<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id', 'product_id', 'product_name', 
        'price', 'quantity', 'subtotal'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getItemsByOrder(int $orderId): array
    {
        return $this->select('order_items.*, products.image, products.slug as product_slug')
                    ->join('products', 'products.id = order_items.product_id', 'left')
                    ->where('order_items.order_id', $orderId)
                    ->findAll();
    }
}

