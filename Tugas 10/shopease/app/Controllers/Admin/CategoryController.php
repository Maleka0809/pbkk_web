<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class CategoryController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel  = new ProductModel();
        helper(['form', 'url']);
    }

    /**
     * Menampilkan daftar kategori produk beserta jumlah produk terkait (Read)
     */
    public function index()
    {
        $categories = $this->categoryModel->getCategoriesWithCount();

        return view('admin/categories/index', [
            'title'      => 'Manajemen Kategori - ShopEase CRUD',
            'categories' => $categories
        ]);
    }

    /**
     * Menampilkan form tambah kategori (Create Form)
     */
    public function create()
    {
        return view('admin/categories/create', [
            'title'      => 'Tambah Kategori Produk - ShopEase',
            'validation' => session()->getFlashdata('errors')
        ]);
    }

    /**
     * Menyimpan kategori baru (Store Action)
     */
    public function store()
    {
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama kategori wajib diisi.',
                    'min_length' => 'Nama kategori minimal 3 karakter.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim($this->request->getPost('name'));
        $slug = url_title($name, '-', true);

        // Pastikan slug unik
        if ($this->categoryModel->where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }

        $this->categoryModel->insert([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori "' . esc($name) . '" berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori (Edit Form)
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('admin/categories/edit', [
            'title'      => 'Edit Kategori #' . $category['id_category'] . ' - ShopEase',
            'category'   => $category,
            'validation' => session()->getFlashdata('errors')
        ]);
    }

    /**
     * Menyimpan perubahan data kategori (Update Action)
     */
    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim($this->request->getPost('name'));

        $this->categoryModel->update($id, [
            'name'        => $name,
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori #' . $id . ' berhasil diperbarui.');
    }

    /**
     * Menghapus kategori (Delete Action dengan validasi relasi Foreign Key)
     */
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        // Cek apakah ada produk yang masih memakai kategori ini
        $productCount = $this->productModel->where('id_category', $id)->countAllResults();
        if ($productCount > 0) {
            return redirect()->to('/admin/categories')->with('error', 'Gagal menghapus! Kategori "' . esc($category['name']) . '" masih digunakan oleh ' . $productCount . ' produk.');
        }

        $this->categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori "' . esc($category['name']) . '" berhasil dihapus.');
    }
}
