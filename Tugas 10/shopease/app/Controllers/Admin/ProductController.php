<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;
    protected ProductImageModel $imageModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->imageModel    = new ProductImageModel();
        helper(['form', 'url', 'text']);
    }

    /**
     * Menampilkan daftar produk (Read) dengan Search & Filter Kategori
     */
    public function index()
    {
        $search   = $this->request->getGet('search');
        $category = $this->request->getGet('category');

        $products = $this->productModel->getProductsWithDetails($search, $category);
        $categories = $this->categoryModel->findAll();

        return view('admin/products/index', [
            'title'      => 'Manajemen Produk - ShopEase CRUD',
            'products'   => $products,
            'categories' => $categories,
            'search'     => $search,
            'selectedCategory' => $category
        ]);
    }

    /**
     * Menampilkan formulir tambah produk baru (Create Form)
     */
    public function create()
    {
        return view('admin/products/create', [
            'title'      => 'Tambah Produk Baru - ShopEase',
            'categories' => $this->categoryModel->findAll(),
            'validation' => session()->getFlashdata('errors')
        ]);
    }

    /**
     * Menyimpan produk baru ke database (Store Action)
     */
    public function store()
    {
        $rules = [
            'id_category' => [
                'rules'  => 'required|is_natural_no_zero',
                'errors' => ['required' => 'Kategori produk wajib dipilih.']
            ],
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama produk wajib diisi.',
                    'min_length' => 'Nama produk minimal 3 karakter.'
                ]
            ],
            'price' => [
                'rules'  => 'required|numeric|greater_than_equal_to[0]',
                'errors' => ['required' => 'Harga produk wajib diisi angka valid.']
            ],
            'stock' => [
                'rules'  => 'required|is_natural',
                'errors' => ['required' => 'Jumlah stok wajib berupa bilangan bulat positif.']
            ],
            'status' => [
                'rules'  => 'required|in_list[tersedia,tidak_tersedia]',
                'errors' => ['required' => 'Status ketersediaan produk wajib dipilih.']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim($this->request->getPost('name'));
        $slug = url_title($name, '-', true) . '-' . time();

        $productId = $this->productModel->insert([
            'id_category' => (int) $this->request->getPost('id_category'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'status'      => $this->request->getPost('status')
        ]);

        // Simpan gambar-gambar yang diupload
        $this->saveImages($productId);

        return redirect()->to('/admin/products')->with('success', 'Produk "' . esc($name) . '" berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit produk (Edit Form)
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        $images = $this->imageModel->where('id_product', $id)->findAll();

        return view('admin/products/edit', [
            'title'      => 'Edit Produk #' . $product['id_product'] . ' - ShopEase',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
            'images'     => $images,
            'validation' => session()->getFlashdata('errors')
        ]);
    }

    /**
     * Menyimpan perubahan data produk ke database (Update Action)
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'id_category' => 'required|is_natural_no_zero',
            'name'        => 'required|min_length[3]|max_length[150]',
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|is_natural',
            'status'      => 'required|in_list[tersedia,tidak_tersedia]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim($this->request->getPost('name'));

        $this->productModel->update($id, [
            'id_category' => (int) $this->request->getPost('id_category'),
            'name'        => $name,
            'description' => $this->request->getPost('description'),
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'status'      => $this->request->getPost('status')
        ]);

        $this->saveImages($id);

        return redirect()->to('/admin/products')->with('success', 'Data produk #' . $id . ' berhasil diperbarui.');
    }

    /**
     * Menghapus produk dan berkas gambarnya (Delete Action)
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        // Hapus seluruh file fisik gambar produk
        $images = $this->imageModel->where('id_product', $id)->findAll();
        foreach ($images as $img) {
            $path = FCPATH . 'uploads/products/' . $img['image'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        // Hapus record dari database (foreign key cascade akan menghapus product_images)
        $this->productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Produk "' . esc($product['name']) . '" berhasil dihapus.');
    }

    /**
     * Menghapus satu gambar tertentu dari galeri
     */
    public function deleteImage($idImage)
    {
        $image = $this->imageModel->find($idImage);
        if ($image) {
            $path = FCPATH . 'uploads/products/' . $image['image'];
            if (is_file($path)) {
                @unlink($path);
            }
            $this->imageModel->delete($idImage);
            return redirect()->back()->with('success', 'Gambar produk berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
    }

    /**
     * Menetapkan gambar utama produk (is_primary = 1)
     */
    public function setPrimaryImage($idProduct, $idImage)
    {
        // Reset gambar utama lama
        $this->imageModel->where('id_product', $idProduct)->set(['is_primary' => 0])->update();
        // Set gambar yang dipilih sebagai utama
        $this->imageModel->update($idImage, ['is_primary' => 1]);

        return redirect()->back()->with('success', 'Gambar utama berhasil diubah.');
    }

    /**
     * Logika upload dan penyimpanan file gambar produk ke server
     */
    private function saveImages($productId)
    {
        $files = $this->request->getFileMultiple('images');
        if (!$files) {
            return;
        }

        $uploadPath = FCPATH . 'uploads/products/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $existingCount = $this->imageModel->where('id_product', $productId)->countAllResults();

        foreach ($files as $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $mime = $file->getMimeType();
            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'], true)) {
                continue;
            }

            if ($file->getSizeByUnit('mb') > 3) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            $this->imageModel->insert([
                'id_product' => $productId,
                'image'      => $newName,
                'is_primary' => ($existingCount === 0) ? 1 : 0
            ]);
            $existingCount++;
        }
    }
}
