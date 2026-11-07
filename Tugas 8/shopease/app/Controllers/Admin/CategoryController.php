<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->getCategoriesWithCount();

        return view('admin/categories/index', [
            'title'      => 'Kelola Kategori - Admin ShopEase',
            'categories' => $categories
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'required|min_length[2]|max_length[120]|is_unique[categories.slug]',
            'icon' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('slug'), '-', true),
            'description' => $this->request->getPost('description'),
            'icon'        => $this->request->getPost('icon') ?: 'bi-box-seam',
        ]);

        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(int $id)
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => "required|min_length[2]|max_length[120]|is_unique[categories.slug,id,{$id}]",
            'icon' => 'permit_empty|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('slug'), '-', true),
            'description' => $this->request->getPost('description'),
            'icon'        => $this->request->getPost('icon') ?: 'bi-box-seam',
        ]);

        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $productModel = new \App\Models\ProductModel();
        $hasProducts  = $productModel->where('category_id', $id)->countAllResults();

        if ($hasProducts > 0) {
            return redirect()->to(base_url('admin/categories'))->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $hasProducts . ' produk terkait.');
        }

        $this->categoryModel->delete($id);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dihapus!');
    }
}

