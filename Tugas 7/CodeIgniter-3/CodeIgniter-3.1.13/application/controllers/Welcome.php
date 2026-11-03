<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$data['judul_kategori'] = 'Semua Produk Elektronik & Gadget';
		$data['barang'] = $this->model_barang->tampil_data()->result();
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('dashboard', $data);
		$this->load->view('templates/footer');
	}

	public function search()
	{
		$keyword = $this->input->post('keyword', TRUE);
		$data['judul_kategori'] = 'Hasil Pencarian: "' . htmlspecialchars($keyword) . '"';
		$data['barang'] = $this->model_barang->get_keyword($keyword);
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('dashboard', $data);
		$this->load->view('templates/footer');
	}
}
