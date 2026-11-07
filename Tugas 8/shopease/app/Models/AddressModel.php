<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table            = 'addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'recipient_name', 'phone', 
        'address_line', 'city', 'postal_code', 'is_default'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUserAddresses(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('is_default', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }
}

