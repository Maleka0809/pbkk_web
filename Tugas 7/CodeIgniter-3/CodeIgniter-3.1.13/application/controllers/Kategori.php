<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function laptop()
    {
        $data['judul_kategori'] = 'Kategori: Laptop & Ultrabook';
        $data['barang'] = $this->model_kategori->data_laptop()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function smartphone()
    {
        $data['judul_kategori'] = 'Kategori: Smartphone Flagship';
        $data['barang'] = $this->model_kategori->data_smartphone()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function aksesoris()
    {
        $data['judul_kategori'] = 'Kategori: Aksesoris & Audio';
        $data['barang'] = $this->model_kategori->data_aksesoris()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function gaming()
    {
        $data['judul_kategori'] = 'Kategori: Konsol & Gaming Gear';
        $data['barang'] = $this->model_kategori->data_gaming()->result();
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer');
    }
}

