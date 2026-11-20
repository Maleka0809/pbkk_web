<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;

class CartController extends BaseController
{
    protected CartModel $cartModel;
    protected CartItemModel $cartItemModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->cartModel     = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->productModel  = new ProductModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);
        $totals = $this->cartItemModel->getCartTotals($cart['id']);

        return view('frontend/cart/index', [
            'title'       => 'Keranjang Belanja - ShopEase',
            'cart'        => $cart,
            'items'       => $totals['items'],
            'totalItems'  => $totals['total_items'],
            'totalPrice'  => $totals['total_price'],
        ]);
    }

    public function add()
    {
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) ($this->request->getPost('quantity') ?? 1);

        if ($quantity < 1) {
            $quantity = 1;
        }

        $product = $this->productModel->find($productId);
        if (!$product || $product['status'] !== 'active') {
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak tersedia.');
        }

        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);

        // Check if item already in cart
        $existing = $this->cartItemModel
            ->where('cart_id', $cart['id'])
            ->where('product_id', $productId)
            ->first();

        $newQty = $existing ? ($existing['quantity'] + $quantity) : $quantity;

        if ($newQty > $product['stock']) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia (Sisa stok: ' . $product['stock'] . ')');
        }

        if ($existing) {
            $this->cartItemModel->update($existing['id'], ['quantity' => $newQty]);
        } else {
            $this->cartItemModel->insert([
                'cart_id'    => $cart['id'],
                'product_id' => $productId,
                'quantity'   => $newQty
            ]);
        }

        return redirect()->to(base_url('cart'))->with('success', esc($product['name']) . ' berhasil ditambahkan ke keranjang!');
    }

    public function update()
    {
        $itemId   = (int) $this->request->getPost('item_id');
        $quantity = (int) $this->request->getPost('quantity');

        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);

        $item = $this->cartItemModel->where('cart_id', $cart['id'])->find($itemId);
        if (!$item) {
            return redirect()->to(base_url('cart'))->with('error', 'Item keranjang tidak ditemukan.');
        }

        $product = $this->productModel->find($item['product_id']);

        if ($quantity <= 0) {
            $this->cartItemModel->delete($itemId);
            return redirect()->to(base_url('cart'))->with('info', 'Item telah dihapus dari keranjang.');
        }

        if ($quantity > $product['stock']) {
            return redirect()->to(base_url('cart'))->with('error', 'Kuantitas melebihi stok tersedia (' . $product['stock'] . ' unit).');
        }

        $this->cartItemModel->update($itemId, ['quantity' => $quantity]);
        return redirect()->to(base_url('cart'))->with('success', 'Kuantitas berhasil diperbarui.');
    }

    public function delete(int $itemId)
    {
        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);

        $this->cartItemModel->where('cart_id', $cart['id'])->delete($itemId);
        return redirect()->to(base_url('cart'))->with('info', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        $userId = session()->get('user_id');
        $cart   = $this->cartModel->getOrCreateCart($userId);

        $this->cartItemModel->where('cart_id', $cart['id'])->delete();
        return redirect()->to(base_url('cart'))->with('info', 'Seluruh keranjang berhasil dikosongkan.');
    }
}

