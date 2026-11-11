<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $categories     = $categoryModel->getCategoriesWithCount();
        $latestProducts = $productModel->getActiveProducts(8, null, null, 'latest');
        $featuredGaming = $productModel->getActiveProducts(4, 4, null, 'latest');

        return view('frontend/home', [
            'title'          => 'ShopEase - Toko Online Terlengkap & Terpercaya',
            'categories'     => $categories,
            'latestProducts' => $latestProducts,
            'featuredGaming' => $featuredGaming,
        ]);
    }
}

