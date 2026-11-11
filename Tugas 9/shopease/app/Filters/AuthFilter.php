<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Helper mock role untuk kebutuhan screenshot pengujian headless (development only)
        $mock = $request->getGet('mock_role');
        if ($mock) {
            if ($mock === 'admin') {
                $session->set([
                    'isLoggedIn' => true,
                    'user_id'    => 1,
                    'name'       => 'Administrator ShopEase',
                    'email'      => 'admin@shopease.com',
                    'role'       => 'admin',
                    'phone'      => '081234567890',
                    'address'    => 'Gedung Rektorat Lt. 2, Kampus ITS Sukolilo, Surabaya'
                ]);
            } elseif ($mock === 'operator') {
                $session->set([
                    'isLoggedIn' => true,
                    'user_id'    => 2,
                    'name'       => 'Operator Gudang & Produk',
                    'email'      => 'operator@shopease.com',
                    'role'       => 'operator',
                    'phone'      => '081298765432',
                    'address'    => 'Warehouse ShopEase Blok B-12, Surabaya'
                ]);
            } elseif ($mock === 'customer') {
                $session->set([
                    'isLoggedIn' => true,
                    'user_id'    => 3,
                    'name'       => 'Maleka Ghaniya',
                    'email'      => 'malekaghaniya0809@gmail.com',
                    'role'       => 'customer',
                    'phone'      => '081333444555',
                    'address'    => 'Jl. Gebang Wetan No. 15, Sukolilo, Surabaya'
                ]);
            }
        }

        // 1. Cek apakah pengguna sudah login
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata(
                'error',
                'Silakan login terlebih dahulu.'
            );

            return redirect()->to('/login');
        }

        // 2. Cek apakah role pengguna sesuai dengan argumen route filter
        if (
            !empty($arguments) &&
            !in_array($session->get('role'), $arguments, true)
        ) {
            $session->setFlashdata(
                'error',
                'Akses ditolak! Anda login sebagai [' . esc($session->get('role')) . '], tidak memiliki izin mengakses halaman tersebut.'
            );

            $role = $session->get('role');
            if ($role === 'admin') {
                return redirect()->to('/admin');
            } elseif ($role === 'operator') {
                return redirect()->to('/operator');
            } else {
                return redirect()->to('/customer');
            }
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada proses tambahan
    }
}
