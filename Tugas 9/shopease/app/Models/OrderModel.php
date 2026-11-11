<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number', 'user_id', 'address_id', 
        'total_amount', 'shipping_cost', 'grand_total', 
        'status', 'customer_notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    public function getOrdersWithDetails($userId = null, $status = null)
    {
        $builder = $this->select('orders.*, users.name as customer_name, users.email as customer_email, users.phone as customer_phone, addresses.recipient_name, addresses.address_line, addresses.city, addresses.postal_code, payments.status as payment_status, payments.payment_method, shipments.status as shipment_status, shipments.courier, shipments.tracking_number')
                        ->join('users', 'users.id = orders.user_id')
                        ->join('addresses', 'addresses.id = orders.address_id', 'left')
                        ->join('payments', 'payments.order_id = orders.id', 'left')
                        ->join('shipments', 'shipments.order_id = orders.id', 'left');

        if ($userId) {
            $builder->where('orders.user_id', $userId);
        }

        if ($status) {
            $builder->where('orders.status', $status);
        }

        return $builder->orderBy('orders.id', 'DESC')->findAll();
    }

    public function getOrderDetail(int $orderId, $userId = null)
    {
        $builder = $this->select('orders.*, users.name as customer_name, users.email as customer_email, users.phone as customer_phone, addresses.recipient_name, addresses.phone as recipient_phone, addresses.address_line, addresses.city, addresses.postal_code, payments.id as payment_id, payments.payment_method, payments.bank_name, payments.account_number, payments.account_holder, payments.amount as payment_amount, payments.proof_image, payments.status as payment_status, payments.rejection_reason, shipments.id as shipment_id, shipments.courier, shipments.service as shipment_service, shipments.tracking_number, shipments.status as shipment_status, shipments.shipped_at, shipments.delivered_at, shipments.notes as shipment_notes')
                        ->join('users', 'users.id = orders.user_id')
                        ->join('addresses', 'addresses.id = orders.address_id', 'left')
                        ->join('payments', 'payments.order_id = orders.id', 'left')
                        ->join('shipments', 'shipments.order_id = orders.id', 'left')
                        ->where('orders.id', $orderId);

        if ($userId) {
            $builder->where('orders.user_id', $userId);
        }

        return $builder->first();
    }
}

