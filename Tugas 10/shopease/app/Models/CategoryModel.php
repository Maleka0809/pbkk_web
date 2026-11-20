<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id_category';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'slug', 'description'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Hitung total produk pada kategori tertentu
     */
    public function getCategoriesWithCount()
    {
        return $this->select('categories.*, COUNT(products.id_product) AS product_count')
                    ->join('products', 'products.id_category = categories.id_category', 'left')
                    ->groupBy('categories.id_category')
                    ->orderBy('categories.name', 'ASC')
                    ->findAll();
    }
}
