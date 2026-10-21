using BankSoalNET.Models;
using BankSoalNET.Services;
using System;
using System.Linq;
using System.Windows;
using System.Windows.Controls;

namespace BankSoalNET.Views
{
    public partial class KelolaMatkulView : UserControl
    {
        private readonly DatabaseService _db;
        private MataKuliah? _selectedMatkul;

        public KelolaMatkulView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
        }

        public async void LoadData()
        {
            try
            {
                var list = await _db.GetAllMataKuliahAsync();
                GridMatkul.ItemsSource = list;
                ResetForm();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal memuat mata kuliah: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void BtnTambah_Click(object sender, RoutedEventArgs e)
        {
            ResetForm();
            TxtKode.Focus();
        }

        private void BtnRowEdit_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is MataKuliah mk)
            {
                _selectedMatkul = mk;
                TxtFormTitle.Text = $"Edit Mata Kuliah: {mk.Kode}";
                TxtKode.Text = mk.Kode;
                TxtNama.Text = mk.Nama;
                TxtSingkatan.Text = mk.Singkatan;
                TxtSks.Text = mk.Sks.ToString();
                TxtSemester.Text = mk.Semester.ToString();
                TxtIcon.Text = mk.Icon;
                TxtDeskripsi.Text = mk.Deskripsi;

                foreach (ComboBoxItem item in CmbWarna.Items)
                {
                    if (item.Tag?.ToString() == mk.Warna)
                    {
                        CmbWarna.SelectedItem = item;
                        break;
                    }
                }

                BtnSimpan.Content = "Simpan Perubahan";
            }
        }

        private async void BtnRowDelete_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is MataKuliah mk)
            {
                var res = MessageBox.Show(
                    $"Hapus mata kuliah '{mk.Nama}'?\nSemua materi dan soal di bawah mata kuliah ini juga akan terhapus.",
                    "Konfirmasi Hapus",
                    MessageBoxButton.YesNo,
                    MessageBoxImage.Warning);

                if (res == MessageBoxResult.Yes)
                {
                    await _db.DeleteMataKuliahAsync(mk.Id);
                    LoadData();
                }
            }
        }

        private async void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            string kode = TxtKode.Text.Trim().ToUpper();
            string nama = TxtNama.Text.Trim();
            string singkatan = TxtSingkatan.Text.Trim();
            if (string.IsNullOrEmpty(singkatan)) singkatan = nama;

            if (string.IsNullOrEmpty(kode) || string.IsNullOrEmpty(nama))
            {
                MessageBox.Show("Kode dan Nama Mata Kuliah wajib diisi!", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
                return;
            }

            int.TryParse(TxtSks.Text, out int sks);
            if (sks <= 0) sks = 3;

            int.TryParse(TxtSemester.Text, out int semester);
            if (semester <= 0) semester = 1;

            string icon = string.IsNullOrWhiteSpace(TxtIcon.Text) ? "📚" : TxtIcon.Text.Trim();
            string warna = (CmbWarna.SelectedItem as ComboBoxItem)?.Tag?.ToString() ?? "purple";
            string deskripsi = TxtDeskripsi.Text.Trim();

            try
            {
                if (_selectedMatkul == null)
                {
                    var newMk = new MataKuliah
                    {
                        Kode = kode,
                        Nama = nama,
                        Singkatan = singkatan,
                        Sks = sks,
                        Semester = semester,
                        Icon = icon,
                        Warna = warna,
                        Deskripsi = deskripsi
                    };
                    await _db.AddMataKuliahAsync(newMk);
                    MessageBox.Show("Mata kuliah berhasil ditambahkan!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }
                else
                {
                    _selectedMatkul.Kode = kode;
                    _selectedMatkul.Nama = nama;
                    _selectedMatkul.Singkatan = singkatan;
                    _selectedMatkul.Sks = sks;
                    _selectedMatkul.Semester = semester;
                    _selectedMatkul.Icon = icon;
                    _selectedMatkul.Warna = warna;
                    _selectedMatkul.Deskripsi = deskripsi;

                    await _db.UpdateMataKuliahAsync(_selectedMatkul);
                    MessageBox.Show("Mata kuliah berhasil diperbarui!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }

                LoadData();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal menyimpan: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void BtnBatal_Click(object sender, RoutedEventArgs e)
        {
            ResetForm();
        }

        private void ResetForm()
        {
            _selectedMatkul = null;
            TxtFormTitle.Text = "Form Tambah Mata Kuliah";
            TxtKode.Text = "";
            TxtNama.Text = "";
            TxtSingkatan.Text = "";
            TxtSks.Text = "3";
            TxtSemester.Text = "4";
            TxtIcon.Text = "⚡";
            TxtDeskripsi.Text = "";
            CmbWarna.SelectedIndex = 0;
            BtnSimpan.Content = "Simpan Matkul";
        }
    }
}
