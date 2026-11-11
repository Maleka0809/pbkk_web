<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ShipmentModel;

class ShipmentController extends BaseController
{
    protected ShipmentModel $shipmentModel;
    protected OrderModel $orderModel;

    public function __construct()
    {
        $this->shipmentModel = new ShipmentModel();
        $this->orderModel    = new OrderModel();
    }

    public function index()
    {
        $shipments = $this->shipmentModel
            ->select('shipments.*, orders.order_number, orders.status as order_status, users.name as customer_name, addresses.recipient_name, addresses.city')
            ->join('orders', 'orders.id = shipments.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->join('addresses', 'addresses.id = orders.address_id', 'left')
            ->orderBy('shipments.id', 'DESC')
            ->findAll();

        return view('admin/shipments/index', [
            'title'     => 'Kelola Pengiriman - Admin ShopEase',
            'shipments' => $shipments
        ]);
    }

    public function update(int $id)
    {
        $shipment = $this->shipmentModel->find($id);
        if (!$shipment) {
            return redirect()->back()->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $trackingNumber = $this->request->getPost('tracking_number');
        $status         = $this->request->getPost('status');
        $notes          = $this->request->getPost('notes');

        $data = [
            'tracking_number' => $trackingNumber,
            'status'          => $status,
            'notes'           => $notes,
        ];

        if ($status === 'in_transit' && empty($shipment['shipped_at'])) {
            $data['shipped_at'] = date('Y-m-d H:i:s');
            $this->orderModel->update($shipment['order_id'], ['status' => 'shipped']);
        } elseif ($status === 'delivered') {
            $data['delivered_at'] = date('Y-m-d H:i:s');
            $this->orderModel->update($shipment['order_id'], ['status' => 'completed']);
        }

        $this->shipmentModel->update($id, $data);

        return redirect()->back()->with('success', 'Informasi pengiriman berhasil diperbarui!');
    }
}

