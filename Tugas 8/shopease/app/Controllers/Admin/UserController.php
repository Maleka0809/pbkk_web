<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $users     = $userModel->orderBy('role', 'ASC')->orderBy('id', 'DESC')->findAll();

        return view('admin/users/index', [
            'title' => 'Data Pengguna - Admin ShopEase',
            'users' => $users,
        ]);
    }
}

