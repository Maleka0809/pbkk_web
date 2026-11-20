<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();
        $userModel     = new UserModel();

        $totalProducts   = $productModel->countAllResults();
        $totalCategories = $categoryModel->countAllResults();
        $availableProducts = $productModel->where('status', 'tersedia')->where('stock >', 0)->countAllResults();
        $outOfStock      = $productModel->where('stock', 0)->orWhere('status', 'tidak_tersedia')->countAllResults();
        $lowStockProducts = $productModel->where('stock <', 15)->findAll();

        $recentProducts = $productModel->getProductsWithDetails();

        return view('admin/dashboard', [
            'title'             => 'Dashboard Product Management - ShopEase CRUD',
            'totalProducts'     => $totalProducts,
            'totalCategories'   => $totalCategories,
            'availableProducts' => $availableProducts,
            'outOfStock'        => $outOfStock,
            'lowStockProducts'  => $lowStockProducts,
            'recentProducts'    => array_slice($recentProducts, 0, 6)
        ]);
    }
}
