<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman registrasi (GET /register)
     */
    public function register()
    {
        if ($this->request->getGet('demo') === 'error') {
            $validation = \Config\Services::validation();
            $validation->setError('password', 'Password minimal 8 karakter.');
            $validation->setError('password_confirm', 'Konfirmasi password tidak sama.');
            $validation->setError('email', 'Email sudah terdaftar.');
            return view('auth/register', [
                'title'      => 'Registrasi Akun - ShopEase',
                'validation' => $validation
            ]);
        }

        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            if ($role === 'admin') {
                return redirect()->to('/admin');
            } elseif ($role === 'operator') {
                return redirect()->to('/operator');
            }
            return redirect()->to('/customer');
        }

        return view('auth/register', [
            'title' => 'Registrasi Akun - ShopEase'
        ]);
    }

    /**
     * Menyimpan akun customer baru (POST /register)
     */
    public function store()
    {
        $rules = [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama wajib diisi.',
                    'min_length' => 'Nama minimal 3 karakter.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email sudah terdaftar.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.'
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak sama.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'title'      => 'Registrasi Akun - ShopEase',
                'validation' => $this->validator
            ]);
        }

        $this->userModel->insert([
            'name' => trim($this->request->getPost('name')),
            'email' => strtolower(trim($this->request->getPost('email'))),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'customer',
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address')
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login menggunakan akun baru Anda.');
    }

    /**
     * Menampilkan halaman login (GET /login)
     */
    public function login()
    {
        if ($this->request->getGet('demo') === 'error') {
            session()->setFlashdata('error', 'Email atau password salah.');
        } elseif ($this->request->getGet('demo') === 'registered') {
            session()->setFlashdata('success', 'Registrasi berhasil. Silakan login menggunakan akun baru Anda.');
        } elseif ($this->request->getGet('demo') === 'logout') {
            session()->setFlashdata('success', 'Anda berhasil logout.');
        } elseif ($this->request->getGet('demo') === 'unauthorized') {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            if ($role === 'admin') {
                return redirect()->to('/admin');
            } elseif ($role === 'operator') {
                return redirect()->to('/operator');
            }
            return redirect()->to('/customer');
        }

        return view('auth/login', [
            'title' => 'Login Pengguna - ShopEase'
        ]);
    }

    /**
     * Memproses login pengguna (POST /login)
     */
    public function authenticate()
    {
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'title'      => 'Login Pengguna - ShopEase',
                'validation' => $this->validator
            ]);
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $user = $this->userModel
            ->where('email', $email)
            ->first();

        if (
            !$user ||
            !password_verify($password, $user['password'])
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Regenerasi session ID setelah autentikasi berhasil
        session()->regenerate(true);

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id_user'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'phone'      => $user['phone'] ?? '',
            'address'    => $user['address'] ?? ''
        ]);

        // Redirect sesuai role
        switch ($user['role']) {
            case 'admin':
                return redirect()->to('/admin')->with('success', 'Selamat datang Administrator, ' . esc($user['name']));
            case 'operator':
                return redirect()->to('/operator')->with('success', 'Selamat datang Operator, ' . esc($user['name']));
            default:
                return redirect()->to('/customer')->with('success', 'Selamat datang, ' . esc($user['name']));
        }
    }

    /**
     * Mengakhiri session pengguna (POST or GET /logout)
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda berhasil logout.');
    }
}
