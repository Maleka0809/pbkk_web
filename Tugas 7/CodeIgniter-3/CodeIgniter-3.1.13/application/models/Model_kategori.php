<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_kategori extends CI_Model {

    public function data_laptop()
    {
        return $this->db->get_where("tb_barang", array('kategori' => 'Laptop'));
    }

    public function data_smartphone()
    {
        return $this->db->get_where("tb_barang", array('kategori' => 'Smartphone'));
    }

    public function data_aksesoris()
    {
        return $this->db->get_where("tb_barang", array('kategori' => 'Aksesoris'));
    }

    public function data_gaming()
    {
        return $this->db->get_where("tb_barang", array('kategori' => 'Gaming'));
    }
}

