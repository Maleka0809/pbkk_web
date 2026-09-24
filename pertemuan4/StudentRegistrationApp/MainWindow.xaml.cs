using System;
using System.Collections.ObjectModel;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using StudentRegistrationApp.Models;

namespace StudentRegistrationApp
{
    public partial class MainWindow : Window
    {
        private ObservableCollection<Student> _mahasiswaList;
        private Student _selectedMahasiswa;
        private bool _isEditMode = false;

        public MainWindow()
        {
            InitializeComponent();
            _mahasiswaList = new ObservableCollection<Student>();
            dgMahasiswa.ItemsSource = _mahasiswaList;
            UpdateJumlahMahasiswa();
        }

        private void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            if (!ValidateInput()) return;

            var student = new Student
            {
                Nim = txtNim.Text,
                Nama = txtNama.Text,
                Prodi = (cmbProdi.SelectedItem as ComboBoxItem)?.Content.ToString(),
                JenisKelamin = rbLaki.IsChecked == true ? "Laki-laki" : "Perempuan",
                TanggalLahir = dpTanggalLahir.SelectedDate,
                Alamat = txtAlamat.Text,
                NoTelepon = txtNoTelepon.Text
            };

            _mahasiswaList.Add(student);
            MessageBox.Show("Data mahasiswa berhasil disimpan!", "Informasi", MessageBoxButton.OK, MessageBoxImage.Information);
            
            ResetForm();
            UpdateJumlahMahasiswa();
        }

        private void BtnUpdate_Click(object sender, RoutedEventArgs e)
        {
            if (_selectedMahasiswa == null || !_isEditMode) return;
            if (!ValidateInput()) return;

            _selectedMahasiswa.Nim = txtNim.Text;
            _selectedMahasiswa.Nama = txtNama.Text;
            _selectedMahasiswa.Prodi = (cmbProdi.SelectedItem as ComboBoxItem)?.Content.ToString();
            _selectedMahasiswa.JenisKelamin = rbLaki.IsChecked == true ? "Laki-laki" : "Perempuan";
            _selectedMahasiswa.TanggalLahir = dpTanggalLahir.SelectedDate;
            _selectedMahasiswa.Alamat = txtAlamat.Text;
            _selectedMahasiswa.NoTelepon = txtNoTelepon.Text;

            MessageBox.Show("Data mahasiswa berhasil diupdate!", "Informasi", MessageBoxButton.OK, MessageBoxImage.Information);
            
            ResetForm();
            dgMahasiswa.Items.Refresh();
        }

        private void BtnReset_Click(object sender, RoutedEventArgs e)
        {
            ResetForm();
        }

        private void BtnEdit_Click(object sender, RoutedEventArgs e)
        {
            var btn = sender as Button;
            var student = btn?.DataContext as Student;
            
            if (student != null)
            {
                _selectedMahasiswa = student;
                _isEditMode = true;

                txtNim.Text = student.Nim;
                txtNama.Text = student.Nama;
                
                foreach (ComboBoxItem item in cmbProdi.Items)
                {
                    if (item.Content.ToString() == student.Prodi)
                    {
                        cmbProdi.SelectedItem = item;
                        break;
                    }
                }

                if (student.JenisKelamin == "Laki-laki") rbLaki.IsChecked = true;
                else if (student.JenisKelamin == "Perempuan") rbPerempuan.IsChecked = true;
                else { rbLaki.IsChecked = false; rbPerempuan.IsChecked = false; }

                dpTanggalLahir.SelectedDate = student.TanggalLahir;
                txtAlamat.Text = student.Alamat;
                txtNoTelepon.Text = student.NoTelepon;

                btnSimpan.IsEnabled = false;
                btnUpdate.IsEnabled = true;
                txtNim.IsReadOnly = true; // Typically NIM cannot be changed
            }
        }

        private void BtnDelete_Click(object sender, RoutedEventArgs e)
        {
            var btn = sender as Button;
            var student = btn?.DataContext as Student;

            if (student != null)
            {
                var result = MessageBox.Show($"Apakah Anda yakin ingin menghapus data {student.Nama}?", "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Question);
                
                if (result == MessageBoxResult.Yes)
                {
                    _mahasiswaList.Remove(student);
                    UpdateJumlahMahasiswa();
                    
                    if (_isEditMode && _selectedMahasiswa == student)
                    {
                        ResetForm();
                    }
                }
            }
        }

        private void BtnSearch_Click(object sender, RoutedEventArgs e)
        {
            string keyword = txtSearch.Text.ToLower();
            
            if (string.IsNullOrWhiteSpace(keyword))
            {
                dgMahasiswa.ItemsSource = _mahasiswaList;
            }
            else
            {
                var filteredList = _mahasiswaList.Where(s => 
                    (s.Nim != null && s.Nim.ToLower().Contains(keyword)) || 
                    (s.Nama != null && s.Nama.ToLower().Contains(keyword))
                ).ToList();
                
                dgMahasiswa.ItemsSource = filteredList;
            }
        }

        private bool ValidateInput()
        {
            if (string.IsNullOrWhiteSpace(txtNim.Text))
            {
                MessageBox.Show("NIM harus diisi!", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
                txtNim.Focus();
                return false;
            }
            if (string.IsNullOrWhiteSpace(txtNama.Text))
            {
                MessageBox.Show("Nama harus diisi!", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
                txtNama.Focus();
                return false;
            }
            if (cmbProdi.SelectedItem == null)
            {
                MessageBox.Show("Pilih program studi!", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
                return false;
            }
            if (rbLaki.IsChecked != true && rbPerempuan.IsChecked != true)
            {
                MessageBox.Show("Pilih jenis kelamin!", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
                return false;
            }
            return true;
        }

        private void ResetForm()
        {
            txtNim.Clear();
            txtNama.Clear();
            cmbProdi.SelectedIndex = -1;
            rbLaki.IsChecked = false;
            rbPerempuan.IsChecked = false;
            dpTanggalLahir.SelectedDate = null;
            txtAlamat.Clear();
            txtNoTelepon.Clear();
            txtSearch.Clear();
            
            txtNim.IsReadOnly = false;
            btnSimpan.IsEnabled = true;
            btnUpdate.IsEnabled = false;
            
            _isEditMode = false;
            _selectedMahasiswa = null;
            
            dgMahasiswa.ItemsSource = _mahasiswaList; // reset filter if any
        }

        private void UpdateJumlahMahasiswa()
        {
            txtJumlahMahasiswa.Text = $"Jumlah Mahasiswa: {_mahasiswaList.Count}";
        }
    }
}