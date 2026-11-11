<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderItemModel;
use App\Models\OrderModel;

class OrderController extends BaseController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;

    public function __construct()
    {
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $orders = $this->orderModel->getOrdersWithDetails(null, $status);

        return view('admin/orders/index', [
            'title'          => 'Kelola Pesanan - Admin ShopEase',
            'orders'         => $orders,
            'selectedStatus' => $status
        ]);
    }

    public function detail(int $id)
    {
        $order = $this->orderModel->getOrderDetail($id);
        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->getItemsByOrder($id);

        return view('admin/orders/detail', [
            'title' => 'Detail Pesanan #' . $order['order_number'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function updateStatus(int $id)
    {
        $newStatus = $this->request->getPost('status');
        $validStatuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        if (!in_array($newStatus, $validStatuses, true)) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid.');
        }

        $this->orderModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi: ' . strtoupper($newStatus));
    }
}

