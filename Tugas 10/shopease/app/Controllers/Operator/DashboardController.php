<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $products = $productModel->findAll();
        $categories = $categoryModel->findAll();

        return view('operator/dashboard', [
            'title'      => 'Dashboard Operator - ShopEase',
            'products'   => $products,
            'categories' => $categories
        ]);
    }
}

