<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if ($this->request->getGet('demo') === 'forbidden') {
            session()->setFlashdata('error', 'Akses ditolak! Anda login sebagai [customer], tidak memiliki izin mengakses halaman tersebut.');
        }

        $userModel = new UserModel();
        $productModel = new ProductModel();

        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        $products = $productModel->findAll(4);

        return view('customer/dashboard', [
            'title' => 'Dashboard Customer - ShopEase',
            'user' => $user,
            'products' => $products
        ]);
    }
}
