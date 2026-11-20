<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id_product';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_category',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'status'
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil produk dengan relasi kategori dan gambar utama untuk admin
     */
    public function getProductsWithDetails($keyword = null, $categoryId = null)
    {
        $builder = $this->select('products.*, categories.name AS category_name, categories.slug AS category_slug, product_images.image AS primary_image, COALESCE(product_images.image, \'default-product.svg\') AS image')
                        ->join('categories', 'categories.id_category = products.id_category')
                        ->join('product_images', 'product_images.id_product = products.id_product AND product_images.is_primary = 1', 'left');

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('products.name', $keyword)
                    ->orLike('products.description', $keyword)
                    ->groupEnd();
        }

        if (!empty($categoryId)) {
            $builder->where('products.id_category', $categoryId);
        }

        return $builder->orderBy('products.id_product', 'DESC')->findAll();
    }

    /**
     * Ambil produk aktif untuk katalog publik frontend
     */
    public function getActiveProducts($categorySlug = null, $categoryId = null, $keyword = null, $sort = 'latest', $limit = 20)
    {
        $builder = $this->select('products.*, categories.name AS category_name, categories.slug AS category_slug, product_images.image AS primary_image, COALESCE(product_images.image, \'default-product.svg\') AS image')
                        ->join('categories', 'categories.id_category = products.id_category')
                        ->join('product_images', 'product_images.id_product = products.id_product AND product_images.is_primary = 1', 'left')
                        ->where('products.status', 'tersedia');

        if (!empty($categoryId)) {
            $builder->where('products.id_category', $categoryId);
        } elseif (!empty($categorySlug)) {
            $builder->where('categories.slug', $categorySlug);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('products.name', $keyword)
                    ->orLike('products.description', $keyword)
                    ->groupEnd();
        }

        switch ($sort) {
            case 'price_low':
                $builder->orderBy('products.price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('products.price', 'DESC');
                break;
            default:
                $builder->orderBy('products.id_product', 'DESC');
                break;
        }

        return $builder->findAll(is_numeric($limit) ? (int)$limit : 20);
    }
}
