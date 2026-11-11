<?php

namespace App\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;

class OrderController extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected PaymentModel $paymentModel;

    public function __construct()
    {
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel   = new PaymentModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $status = $this->request->getGet('status');

        $orders = $this->orderModel->getOrdersWithDetails($userId, $status);

        return view('frontend/orders/index', [
            'title'          => 'Pesanan Saya - ShopEase',
            'orders'         => $orders,
            'selectedStatus' => $status,
        ]);
    }

    public function detail(int $id)
    {
        $userId = session()->get('user_id');
        $order  = $this->orderModel->getOrderDetail($id, $userId);

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->getItemsByOrder($id);

        return view('frontend/orders/detail', [
            'title' => 'Detail Pesanan #' . $order['order_number'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function uploadPayment(int $id)
    {
        $userId = session()->get('user_id');
        $order  = $this->orderModel->getOrderDetail($id, $userId);

        if (!$order) {
            return redirect()->to(base_url('orders'))->with('error', 'Pesanan tidak ditemukan.');
        }

        $rules = [
            'bank_name'      => 'required|min_length[2]|max_length[50]',
            'account_number' => 'required|min_length[4]|max_length[50]',
            'account_holder' => 'required|min_length[2]|max_length[100]',
            'proof_image'    => 'uploaded[proof_image]|max_size[proof_image,4096]|is_image[proof_image]|ext_in[proof_image,jpg,jpeg,png,webp,svg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $img = $this->request->getFile('proof_image');
        $newName = $img->getRandomName();
        $img->move(FCPATH . 'uploads/payments', $newName);

        $paymentData = [
            'bank_name'      => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_holder' => $this->request->getPost('account_holder'),
            'proof_image'    => $newName,
            'status'         => 'pending'
        ];

        $payment = $this->paymentModel->where('order_id', $id)->first();
        if ($payment) {
            $this->paymentModel->update($payment['id'], $paymentData);
        } else {
            $paymentData['order_id'] = $id;
            $paymentData['amount']   = $order['grand_total'];
            $this->paymentModel->insert($paymentData);
        }

        return redirect()->to(base_url('orders/' . $id))->with('success', 'Bukti pembayaran berhasil diunggah! Administrator akan segera memverifikasi transaksi Anda.');
    }
}

