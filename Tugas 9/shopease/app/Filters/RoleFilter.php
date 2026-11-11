<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Development mock helper for screenshots & preview
        if ($request->getGet('mock_role') === 'admin' || $request->getGet('mock_role') === 'operator') {
            $role = $request->getGet('mock_role');
            $session->set([
                'isLoggedIn' => true,
                'user_id'    => ($role === 'admin' ? 1 : 2),
                'name'       => ($role === 'admin' ? 'Administrator ShopEase' : 'Operator Gudang'),
                'email'      => ($role === 'admin' ? 'admin@shopease.com' : 'operator@shopease.com'),
                'role'       => $role,
                'avatar'     => ($role === 'admin' ? 'avatar-admin.svg' : 'avatar-operator.svg'),
            ]);
        }

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $userRole = $session->get('role');
        $allowedRoles = $arguments ?? ['admin'];

        if (!in_array($userRole, $allowedRoles, true)) {
            $session->setFlashdata('error', 'Akses ditolak! Anda tidak memiliki izin untuk membuka halaman tersebut.');
            return redirect()->to(base_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
