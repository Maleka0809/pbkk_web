<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id', 'name', 'slug', 'description', 
        'price', 'stock', 'image', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'category_id' => 'required|is_natural_no_zero',
        'name'        => 'required|min_length[3]|max_length[150]',
        'slug'        => 'required|min_length[3]|max_length[180]|is_unique[products.slug,id,{id}]',
        'price'       => 'required|numeric|greater_than_equal_to[0]',
        'stock'       => 'required|integer|greater_than_equal_to[0]',
        'status'      => 'required|in_list[active,inactive]',
    ];

    public function getActiveProducts($limit = null, $categoryId = null, $search = null, $sort = 'latest')
    {
        $builder = $this->select('products.*, categories.name as category_name, categories.slug as category_slug')
                        ->join('categories', 'categories.id = products.category_id')
                        ->where('products.status', 'active');

        if (!empty($categoryId)) {
            $builder->where('products.category_id', $categoryId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('products.name', $search)
                    ->orLike('products.description', $search)
                    ->orLike('categories.name', $search)
                    ->groupEnd();
        }

        switch ($sort) {
            case 'price_low':
                $builder->orderBy('products.price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('products.price', 'DESC');
                break;
            case 'name_asc':
                $builder->orderBy('products.name', 'ASC');
                break;
            case 'latest':
            default:
                $builder->orderBy('products.id', 'DESC');
                break;
        }

        if ($limit) {
            return $builder->findAll($limit);
        }

        return $builder->findAll();
    }

    public function getProductBySlug($slug)
    {
        return $this->select('products.*, categories.name as category_name, categories.slug as category_slug')
                    ->join('categories', 'categories.id = products.category_id')
                    ->where('products.slug', $slug)
                    ->first();
    }

    public function getAllProductsWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->orderBy('products.id', 'DESC')
                    ->findAll();
    }
}

