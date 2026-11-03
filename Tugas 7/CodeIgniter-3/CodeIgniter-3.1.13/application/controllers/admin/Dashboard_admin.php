<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_admin extends CI_Controller {

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
        $data['total_barang']  = $this->db->count_all('tb_barang');
        $data['total_invoice'] = $this->db->count_all('tb_invoice');
        $data['total_user']    = $this->db->count_all('tb_user');
        $stok_row              = $this->db->select_sum('stok')->get('tb_barang')->row();
        $data['total_stok']    = $stok_row ? (int)$stok_row->stok : 0;
        $data['invoice_terbaru'] = $this->db->order_by('id', 'DESC')->limit(5)->get('tb_invoice')->result();

        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates_admin/footer');
    }
}

