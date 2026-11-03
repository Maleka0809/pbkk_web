<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index()
    {
        redirect('welcome');
    }

    public function tambah_ke_keranjang($id)
    {
        if ($this->session->userdata('role_id') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Silakan <strong>Login sebagai Pelanggan</strong> terlebih dahulu untuk berbelanja!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('auth/login');
        }

        $barang = $this->model_barang->find($id);

        if ($barang && $barang->stok > 0) {
            $data = array(
                'id'      => $barang->id_brg,
                'qty'     => 1,
                'price'   => $barang->harga,
                'name'    => $barang->nama_brg
            );
            $this->cart->insert($data);
            $this->session->set_flashdata('notif_cart', 'Produk <strong>' . htmlspecialchars($barang->nama_brg) . '</strong> berhasil ditambahkan ke keranjang!');
        }
        redirect('welcome');
    }

    public function detail_keranjang()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('keranjang');
        $this->load->view('templates/footer');
    }

    public function hapus_keranjang()
    {
        $this->cart->destroy();
        redirect('welcome');
    }

    public function hapus_item($rowid)
    {
        $this->cart->remove($rowid);
        redirect('dashboard/detail_keranjang');
    }

    public function pembayaran()
    {
        if ($this->session->userdata('role_id') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Silakan Login terlebih dahulu untuk melanjutkan pembayaran!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('auth/login');
        }

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('pembayaran');
        $this->load->view('templates/footer');
    }

    public function proses_pesanan()
    {
        if ($this->session->userdata('role_id') != '2') {
            redirect('auth/login');
        }

        $is_processed = $this->model_invoice->index();
        if ($is_processed) {
            $this->cart->destroy();
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            $this->load->view('proses_pesanan');
            $this->load->view('templates/footer');
        } else {
            echo "Maaf, Pesanan Anda Gagal Diproses!";
        }
    }

    public function detail($id_brg)
    {
        $data['barang'] = $this->model_barang->detail_brg($id_brg);
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('detail_barang', $data);
        $this->load->view('templates/footer');
    }
}

