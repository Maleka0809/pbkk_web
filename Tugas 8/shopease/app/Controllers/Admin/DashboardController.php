<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $orderModel    = new OrderModel();
        $productModel  = new ProductModel();
        $userModel     = new UserModel();
        $paymentModel  = new PaymentModel();
        $categoryModel = new CategoryModel();

        // Calculate total revenue from paid/completed orders
        $revenue = $orderModel->selectSum('grand_total')
                              ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                              ->first()['grand_total'] ?? 0;

        $totalOrders    = $orderModel->countAllResults();
        $totalProducts  = $productModel->countAllResults();
        $totalCustomers = $userModel->where('role', 'customer')->countAllResults();

        $recentOrders    = $orderModel->getOrdersWithDetails(null, null);
        $pendingPayments = $paymentModel->getPendingPayments();
        $lowStockProducts= $productModel->where('stock <', 10)->where('status', 'active')->findAll();

        return view('admin/dashboard', [
            'title'            => 'Dashboard Administrator - ShopEase',
            'revenue'          => (float) $revenue,
            'totalOrders'      => $totalOrders,
            'totalProducts'    => $totalProducts,
            'totalCustomers'   => $totalCustomers,
            'recentOrders'     => array_slice($recentOrders, 0, 5),
            'pendingPayments'  => $pendingPayments,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}

