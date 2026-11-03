<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {

    public function index()
    {
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [
            'required' => 'Nama Lengkap wajib diisi!'
        ]);
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tb_user.username]', [
            'required'  => 'Username wajib diisi!',
            'is_unique' => 'Username sudah terdaftar, silakan gunakan username lain!'
        ]);
        $this->form_validation->set_rules('password_1', 'Password', 'required|matches[password_2]', [
            'required' => 'Password wajib diisi!',
            'matches'  => 'Konfirmasi Password tidak cocok!'
        ]);
        $this->form_validation->set_rules('password_2', 'Ulangi Password', 'required|matches[password_1]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('registrasi');
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'id'       => '',
                'nama'     => $this->input->post('nama', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password_1', TRUE),
                'role_id'  => 2,
            );

            $this->db->insert('tb_user', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Registrasi Akun Berhasil! Silakan Login.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('auth/login');
        }
    }
}

