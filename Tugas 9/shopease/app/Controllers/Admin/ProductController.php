<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $products   = $this->productModel->getAllProductsWithCategory();
        $categories = $this->categoryModel->findAll();

        return view('admin/products/index', [
            'title'      => 'Kelola Produk - Admin ShopEase',
            'products'   => $products,
            'categories' => $categories
        ]);
    }

    public function store()
    {
        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'name'        => 'required|min_length[3]|max_length[150]',
            'slug'        => 'required|min_length[3]|max_length[180]|is_unique[products.slug]',
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'status'      => 'required|in_list[active,inactive]',
            'image'       => 'permit_empty|uploaded[image]|max_size[image,4096]|is_image[image]|ext_in[image,jpg,jpeg,png,webp,svg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = 'default-product.svg';
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $imageName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/products', $imageName);
        }

        $this->productModel->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('slug'), '-', true),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'image'       => $imageName,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('admin/products'))->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function update(int $id)
    {
        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'name'        => 'required|min_length[3]|max_length[150]',
            'slug'        => "required|min_length[3]|max_length[180]|is_unique[products.slug,id,{$id}]",
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'status'      => 'required|in_list[active,inactive]',
            'image'       => 'permit_empty|max_size[image,4096]|is_image[image]|ext_in[image,jpg,jpeg,png,webp,svg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $product = $this->productModel->find($id);
        $imageName = $product['image'];

        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $imageName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/products', $imageName);
        }

        $this->productModel->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('slug'), '-', true),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'image'       => $imageName,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('admin/products'))->with('success', 'Data produk berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $this->productModel->delete($id);
        return redirect()->to(base_url('admin/products'))->with('success', 'Produk berhasil dihapus!');
    }
}

