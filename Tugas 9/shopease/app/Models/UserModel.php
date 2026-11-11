<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari pengguna berdasarkan email
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', strtolower(trim($email)))->first();
    }

    /**
     * Hitung total pengguna berdasarkan role
     */
    public function countByRole(string $role)
    {
        return $this->where('role', $role)->countAllResults();
    }
}
