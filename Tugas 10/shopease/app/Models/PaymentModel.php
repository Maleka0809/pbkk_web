<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id', 'payment_method', 'bank_name', 'account_number', 
        'account_holder', 'amount', 'proof_image', 'status', 
        'verified_by', 'verified_at', 'rejection_reason'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPendingPayments()
    {
        return $this->select('payments.*, orders.order_number, orders.grand_total, users.name as customer_name, users.email as customer_email')
                    ->join('orders', 'orders.id = payments.order_id')
                    ->join('users', 'users.id = orders.user_id')
                    ->where('payments.status', 'pending')
                    ->orderBy('payments.id', 'DESC')
                    ->findAll();
    }
}

