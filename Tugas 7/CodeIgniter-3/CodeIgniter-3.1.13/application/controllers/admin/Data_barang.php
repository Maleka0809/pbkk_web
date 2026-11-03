<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('role_id') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Akses Ditolak! Anda belum login sebagai Administrator.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['barang'] = $this->model_barang->tampil_data()->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/data_barang', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambah_aksi()
    {
        $nama_brg   = $this->input->post('nama_brg', TRUE);
        $keterangan = $this->input->post('keterangan', TRUE);
        $kategori   = $this->input->post('kategori', TRUE);
        $harga      = $this->input->post('harga', TRUE);
        $stok       = $this->input->post('stok', TRUE);
        $gambar     = $_FILES['gambar']['name'];

        if ($gambar == '') {
            $gambar = 'default.svg';
        } else {
            $config['upload_path']   = './uploads';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|svg|webp';
            $config['max_size']      = 4096;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('gambar')) {
                $this->session->set_flashdata('pesan_admin', '<div class="alert alert-danger">Gagal upload gambar: ' . $this->upload->display_errors('', '') . '</div>');
                redirect('admin/data_barang/index');
                return;
            } else {
                $gambar = $this->upload->data('file_name');
            }
        }

        $data = array(
            'nama_brg'   => $nama_brg,
            'keterangan' => $keterangan,
            'kategori'   => $kategori,
            'harga'      => $harga,
            'stok'       => $stok,
            'gambar'     => $gambar
        );

        $this->model_barang->tambah_barang($data, 'tb_barang');
        $this->session->set_flashdata('pesan_admin', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data barang <strong>' . htmlspecialchars($nama_brg) . '</strong> berhasil ditambahkan!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        redirect('admin/data_barang/index');
    }

    public function edit($id)
    {
        $where = array('id_brg' => $id);
        $data['barang'] = $this->model_barang->edit_barang($where, 'tb_barang')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/edit_barang', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update()
    {
        $id         = $this->input->post('id_brg', TRUE);
        $nama_brg   = $this->input->post('nama_brg', TRUE);
        $keterangan = $this->input->post('keterangan', TRUE);
        $kategori   = $this->input->post('kategori', TRUE);
        $harga      = $this->input->post('harga', TRUE);
        $stok       = $this->input->post('stok', TRUE);

        $data = array(
            'nama_brg'   => $nama_brg,
            'keterangan' => $keterangan,
            'kategori'   => $kategori,
            'harga'      => $harga,
            'stok'       => $stok
        );

        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path']   = './uploads';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|svg|webp';
            $config['max_size']      = 4096;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $data['gambar'] = $this->upload->data('file_name');
            }
        }

        $where = array('id_brg' => $id);
        $this->model_barang->update_data($where, $data, 'tb_barang');
        $this->session->set_flashdata('pesan_admin', '<div class="alert alert-info alert-dismissible fade show" role="alert">Data barang berhasil diperbarui!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        redirect('admin/data_barang/index');
    }

    public function hapus($id)
    {
        $where = array('id_brg' => $id);
        $this->model_barang->hapus_data($where, 'tb_barang');
        $this->session->set_flashdata('pesan_admin', '<div class="alert alert-warning alert-dismissible fade show" role="alert">Data barang berhasil dihapus!<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        redirect('admin/data_barang/index');
    }

    public function detail($id_brg)
    {
        $data['barang'] = $this->model_barang->detail_brg($id_brg);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/detail_barang', $data);
        $this->load->view('templates_admin/footer');
    }
}

