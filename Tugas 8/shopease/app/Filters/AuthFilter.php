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

        // Development mock helper for screenshots & preview
        if ($request->getGet('mock_role') === 'customer' || $request->getGet('mock_role') === 'admin') {
            $role = $request->getGet('mock_role');
            $session->set([
                'isLoggedIn' => true,
                'user_id'    => ($role === 'admin' ? 1 : 3),
                'name'       => ($role === 'admin' ? 'Administrator ShopEase' : 'Budi Santoso'),
                'email'      => ($role === 'admin' ? 'admin@shopease.com' : 'budi@gmail.com'),
                'role'       => $role,
                'avatar'     => ($role === 'admin' ? 'avatar-admin.svg' : 'avatar-customer.svg'),
            ]);
        }

        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan masuk (login) terlebih dahulu untuk melanjutkan.');
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
