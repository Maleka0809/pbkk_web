<?php

namespace App\Controllers;

use App\Models\AddressModel;
use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Models\ShipmentModel;

class CheckoutController extends BaseController
{
    protected CartModel $cartModel;
    protected CartItemModel $cartItemModel;
    protected AddressModel $addressModel;
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected PaymentModel $paymentModel;
    protected ShipmentModel $shipmentModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->cartModel      = new CartModel();
        $this->cartItemModel  = new CartItemModel();
        $this->addressModel   = new AddressModel();
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel   = new PaymentModel();
        $this->shipmentModel  = new ShipmentModel();
        $this->productModel   = new ProductModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);
        $totals = $this->cartItemModel->getCartTotals($cart['id']);

        if (empty($totals['items'])) {
            return redirect()->to(base_url('cart'))->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $addresses = $this->addressModel->getUserAddresses($userId);

        return view('frontend/checkout/index', [
            'title'      => 'Checkout Pesanan - ShopEase',
            'items'      => $totals['items'],
            'totalItems' => $totals['total_items'],
            'totalPrice' => $totals['total_price'],
            'addresses'  => $addresses,
        ]);
    }

    public function process()
    {
        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);
        $items  = $this->cartItemModel->getItemsByCart($cart['id']);

        if (empty($items)) {
            return redirect()->to(base_url('cart'))->with('error', 'Keranjang belanja kosong.');
        }

        // Validate stock for all items
        foreach ($items as $item) {
            $prod = $this->productModel->find($item['product_id']);
            if (!$prod || $prod['stock'] < $item['quantity']) {
                return redirect()->to(base_url('cart'))->with('error', 'Stok produk "' . esc($item['product_name']) . '" tidak mencukupi (sisa: ' . ($prod['stock'] ?? 0) . '). Silakan sesuaikan jumlah pembelian.');
            }
        }

        $addressId = $this->request->getPost('address_id');

        // Check if user added a new address on the fly
        if ($addressId === 'new' || empty($addressId)) {
            $newAddress = [
                'user_id'        => $userId,
                'recipient_name' => $this->request->getPost('new_recipient_name'),
                'phone'          => $this->request->getPost('new_phone'),
                'address_line'   => $this->request->getPost('new_address_line'),
                'city'           => $this->request->getPost('new_city'),
                'postal_code'    => $this->request->getPost('new_postal_code'),
                'is_default'     => 1
            ];

            if (empty($newAddress['recipient_name']) || empty($newAddress['address_line']) || empty($newAddress['city'])) {
                return redirect()->back()->withInput()->with('error', 'Mohon lengkapi data alamat pengiriman baru.');
            }

            $addressId = $this->addressModel->insert($newAddress);
        }

        $courier       = $this->request->getPost('courier') ?? 'JNE';
        $shippingCost  = 20000.00;
        $paymentMethod = $this->request->getPost('payment_method') ?? 'Bank Transfer';
        $bankName      = $this->request->getPost('bank_name') ?? 'BCA';
        $customerNotes = $this->request->getPost('customer_notes');

        // Recalculate totals on backend (Never trust client)
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += ((float) $item['price'] * (int) $item['quantity']);
        }
        $grandTotal = $subtotal + $shippingCost;

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Create Order
            $orderNumber = $this->orderModel->generateOrderNumber();
            $orderData = [
                'order_number'   => $orderNumber,
                'user_id'        => $userId,
                'address_id'     => $addressId,
                'total_amount'   => $subtotal,
                'shipping_cost'  => $shippingCost,
                'grand_total'    => $grandTotal,
                'status'         => 'pending',
                'customer_notes' => $customerNotes,
            ];
            $orderId = $this->orderModel->insert($orderData);

            // 2. Insert Order Items (Database trigger trg_reduce_stock_after_order will automatically reduce stock)
            foreach ($items as $item) {
                $itemSubtotal = (float) $item['price'] * (int) $item['quantity'];
                $this->orderItemModel->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $itemSubtotal,
                ]);
            }

            // 3. Create Initial Payment Record
            $this->paymentModel->insert([
                'order_id'       => $orderId,
                'payment_method' => $paymentMethod,
                'bank_name'      => $bankName,
                'amount'         => $grandTotal,
                'status'         => 'pending'
            ]);

            // 4. Create Initial Shipment Record
            $this->shipmentModel->insert([
                'order_id' => $orderId,
                'courier'  => $courier,
                'service'  => 'REG (Reguler)',
                'status'   => 'preparing'
            ]);

            // 5. Clear Cart Items
            $this->cartItemModel->where('cart_id', $cart['id'])->delete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to(base_url('cart'))->with('error', 'Gagal memproses transaksi. Silakan coba kembali.');
            }

            $db->transCommit();

            return redirect()->to(base_url('orders/' . $orderId))->with('success', 'Pesanan ' . $orderNumber . ' berhasil dibuat! Silakan lakukan pembayaran dan unggah bukti transfer.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to(base_url('cart'))->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}

