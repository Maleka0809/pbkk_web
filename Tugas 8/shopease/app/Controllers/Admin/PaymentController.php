<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;

class PaymentController extends BaseController
{
    protected PaymentModel $paymentModel;
    protected OrderModel $orderModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->orderModel   = new OrderModel();
    }

    public function index()
    {
        $payments = $this->paymentModel
            ->select('payments.*, orders.order_number, orders.grand_total, users.name as customer_name, users.email as customer_email')
            ->join('orders', 'orders.id = payments.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('payments.id', 'DESC')
            ->findAll();

        return view('admin/payments/index', [
            'title'    => 'Verifikasi Pembayaran - Admin ShopEase',
            'payments' => $payments
        ]);
    }

    public function verify(int $id)
    {
        $adminId = session()->get('user_id');
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $this->paymentModel->update($id, [
            'status'      => 'verified',
            'verified_by' => $adminId,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        // Update corresponding order status to paid / processing
        $this->orderModel->update($payment['order_id'], ['status' => 'processing']);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi! Pesanan kini berstatus "processing".');
    }

    public function reject(int $id)
    {
        $reason  = $this->request->getPost('reason') ?? 'Bukti transfer tidak terbaca atau nominal tidak sesuai.';
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $this->paymentModel->update($id, [
            'status'           => 'rejected',
            'rejection_reason' => $reason,
        ]);

        return redirect()->back()->with('info', 'Pembayaran telah ditolak dengan alasan: ' . esc($reason));
    }
}

