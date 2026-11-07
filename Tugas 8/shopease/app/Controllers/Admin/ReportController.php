<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class ReportController extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status    = $this->request->getGet('status');

        $builder = $orderModel->select('orders.*, users.name as customer_name')
                              ->join('users', 'users.id = orders.user_id')
                              ->where('DATE(orders.created_at) >=', $startDate)
                              ->where('DATE(orders.created_at) <=', $endDate);

        if ($status) {
            $builder->where('orders.status', $status);
        }

        $orders = $builder->orderBy('orders.id', 'DESC')->findAll();

        $totalRevenue = 0;
        foreach ($orders as $o) {
            if (in_array($o['status'], ['paid', 'processing', 'shipped', 'completed'], true)) {
                $totalRevenue += (float) $o['grand_total'];
            }
        }

        return view('admin/reports/index', [
            'title'        => 'Laporan Penjualan - Admin ShopEase',
            'orders'       => $orders,
            'totalRevenue' => $totalRevenue,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'status'       => $status,
        ]);
    }
}

