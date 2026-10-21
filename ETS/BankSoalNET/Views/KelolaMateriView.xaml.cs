using BankSoalNET.Models;
using BankSoalNET.Services;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows;
using System.Windows.Controls;

namespace BankSoalNET.Views
{
    public partial class KelolaMateriView : UserControl
    {
        private readonly DatabaseService _db;
        private List<MataKuliah> _matkulList = new();
        private List<Materi> _allMateri = new();
        private Materi? _selectedMateri;

        public KelolaMateriView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
        }

        public async void LoadData()
        {
            try
            {
                _matkulList = await _db.GetAllMataKuliahAsync();
                _allMateri = await _db.GetAllMateriAsync();

                // Setup CmbFilterMatkul
                CmbFilterMatkul.Items.Clear();
                CmbFilterMatkul.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
                foreach (var mk in _matkulList)
                {
                    CmbFilterMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan}", Tag = mk.Id });
                }
                CmbFilterMatkul.SelectedIndex = 0;

                // Setup CmbFormMatkul
                CmbFormMatkul.Items.Clear();
                foreach (var mk in _matkulList)
                {
                    CmbFormMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan} - {mk.Nama}", Tag = mk.Id });
                }
                if (CmbFormMatkul.Items.Count > 0) CmbFormMatkul.SelectedIndex = 0;

                ApplyFilter();
                ResetForm();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal memuat materi: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void CmbFilterMatkul_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            ApplyFilter();
        }

        private void ApplyFilter()
        {
            int selectedMatkulId = (CmbFilterMatkul.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;
            var filtered = selectedMatkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == selectedMatkulId)
                : _allMateri;

            GridMateri.ItemsSource = filtered.ToList();
        }

        private void BtnTambah_Click(object sender, RoutedEventArgs e)
        {
            ResetForm();
            TxtPertemuan.Focus();
        }

        private void BtnRowEdit_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is Materi m)
            {
                _selectedMateri = m;
                TxtFormTitle.Text = $"Edit Materi P{m.Pertemuan}";
                TxtPertemuan.Text = m.Pertemuan.ToString();
                TxtJudul.Text = m.Judul;
                TxtDeskripsi.Text = m.Deskripsi;
                TxtTags.Text = m.Tags;
                TxtKonten.Text = m.Konten;

                foreach (ComboBoxItem item in CmbFormMatkul.Items)
                {
                    if (item.Tag is int mkId && mkId == m.MataKuliahId)
                    {
                        CmbFormMatkul.SelectedItem = item;
                        break;
                    }
                }

                BtnSimpan.Content = "Simpan Perubahan";
            }
        }

        private async void BtnRowDelete_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is Materi m)
            {
                var res = MessageBox.Show($"Hapus materi '{m.Judul}' beserta semua soalnya?",
                    "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Warning);
                if (res == MessageBoxResult.Yes)
                {
                    await _db.DeleteMateriAsync(m.Id);
                    _allMateri = await _db.GetAllMateriAsync();
                    ApplyFilter();
                }
            }
        }

        private async void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            if (!int.TryParse(TxtPertemuan.Text, out int pertemuan))
            {
                MessageBox.Show("Pertemuan harus berupa angka!", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
                return;
            }

            string judul = TxtJudul.Text.Trim();
            if (string.IsNullOrEmpty(judul))
            {
                MessageBox.Show("Judul materi wajib diisi!", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
                return;
            }

            int selectedMatkulId = (CmbFormMatkul.SelectedItem as ComboBoxItem)?.Tag is int mkId ? mkId : 1;

            try
            {
                if (_selectedMateri == null)
                {
                    var newMateri = new Materi
                    {
                        MataKuliahId = selectedMatkulId,
                        Pertemuan = pertemuan,
                        Judul = judul,
                        Deskripsi = TxtDeskripsi.Text.Trim(),
                        Tags = TxtTags.Text.Trim(),
                        Konten = TxtKonten.Text.Trim()
                    };
                    await _db.AddMateriAsync(newMateri);
                    MessageBox.Show("Materi baru berhasil ditambahkan!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }
                else
                {
                    _selectedMateri.MataKuliahId = selectedMatkulId;
                    _selectedMateri.Pertemuan = pertemuan;
                    _selectedMateri.Judul = judul;
                    _selectedMateri.Deskripsi = TxtDeskripsi.Text.Trim();
                    _selectedMateri.Tags = TxtTags.Text.Trim();
                    _selectedMateri.Konten = TxtKonten.Text.Trim();

                    await _db.UpdateMateriAsync(_selectedMateri);
                    MessageBox.Show("Materi berhasil diperbarui!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }

                _allMateri = await _db.GetAllMateriAsync();
                ApplyFilter();
                ResetForm();
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
            _selectedMateri = null;
            TxtFormTitle.Text = "Form Tambah Materi";
            TxtPertemuan.Text = "";
            TxtJudul.Text = "";
            TxtDeskripsi.Text = "";
            TxtTags.Text = "";
            TxtKonten.Text = "";
            BtnSimpan.Content = "Simpan Materi";
        }
    }
}
