<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') === 'customer' ? base_url() : base_url('admin/dashboard'));
        }

        return view('auth/login', [
            'title' => 'Masuk Akun - ShopEase'
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password yang Anda masukkan salah!');
        }

        // Set session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => (int) $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'avatar'     => $user['avatar'] ?? 'default-avatar.svg',
        ]);

        session()->setFlashdata('success', 'Selamat datang kembali, ' . esc($user['name']) . '!');

        if (in_array($user['role'], ['admin', 'operator'], true)) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return redirect()->to(base_url());
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url());
        }

        return view('auth/register', [
            'title' => 'Daftar Akun Baru - ShopEase'
        ]);
    }

    public function attemptRegister()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[150]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required|min_length[8]|max_length[20]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => 'customer',
            'avatar'   => 'avatar-customer.svg'
        ];

        $this->userModel->insert($data);

        session()->setFlashdata('success', 'Registrasi berhasil! Silakan masuk dengan akun baru Anda.');
        return redirect()->to(base_url('login'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil keluar (logout).');
    }
}

