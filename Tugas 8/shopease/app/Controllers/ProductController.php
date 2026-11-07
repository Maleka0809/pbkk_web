<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('search');
        $sort       = $this->request->getGet('sort') ?? 'latest';

        $products   = $this->productModel->getActiveProducts(null, $categoryId, $search, $sort);
        $categories = $this->categoryModel->getCategoriesWithCount();

        $currentCategory = null;
        if (!empty($categoryId)) {
            $currentCategory = $this->categoryModel->find($categoryId);
        }

        return view('frontend/products/index', [
            'title'           => 'Katalog Produk - ShopEase',
            'products'        => $products,
            'categories'      => $categories,
            'selectedCategory'=> $categoryId,
            'currentCategory' => $currentCategory,
            'search'          => $search,
            'sort'            => $sort,
            'totalResults'    => count($products),
        ]);
    }

    public function detail(string $slug)
    {
        $product = $this->productModel->getProductBySlug($slug);

        if (!$product || $product['status'] !== 'active') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan atau sedang nonaktif.');
        }

        $relatedProducts = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.category_id', $product['category_id'])
            ->where('products.id !=', $product['id'])
            ->where('products.status', 'active')
            ->findAll(4);

        return view('frontend/products/detail', [
            'title'           => esc($product['name']) . ' - ShopEase',
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

