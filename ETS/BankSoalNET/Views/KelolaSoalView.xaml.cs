using BankSoalNET.Models;
using BankSoalNET.Services;
using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Windows;
using System.Windows.Controls;

namespace BankSoalNET.Views
{
    public partial class KelolaSoalView : UserControl
    {
        private readonly DatabaseService _db;
        private List<MataKuliah> _matkulList = new();
        private List<Materi> _allMateri = new();
        private List<Soal> _allSoal = new();
        private Soal? _selectedSoal;

        private string? _tempGambarPath;

        public KelolaSoalView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
        }

        public async void LoadInitialData()
        {
            try
            {
                _matkulList = await _db.GetAllMataKuliahAsync();
                _allMateri = await _db.GetAllMateriAsync();

                // Setup Filter Matkul
                CmbFilterMatkul.Items.Clear();
                CmbFilterMatkul.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
                foreach (var mk in _matkulList)
                {
                    CmbFilterMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan}", Tag = mk.Id });
                }
                CmbFilterMatkul.SelectedIndex = 0;

                // Setup Form Matkul
                CmbFormMatkul.Items.Clear();
                foreach (var mk in _matkulList)
                {
                    CmbFormMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan} - {mk.Nama}", Tag = mk.Id });
                }
                if (CmbFormMatkul.Items.Count > 0) CmbFormMatkul.SelectedIndex = 0;

                RefreshSoalList();
                ResetForm();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal memuat data: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private async void RefreshSoalList()
        {
            _allSoal = await _db.GetAllSoalAsync();
            ApplyFilter();
        }

        private void ApplyFilter()
        {
            int selectedMatkulId = (CmbFilterMatkul.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;
            int selectedMateriId = (CmbFilterMateri.SelectedItem as ComboBoxItem)?.Tag is int tId ? tId : 0;

            var filtered = _allSoal.AsEnumerable();

            if (selectedMatkulId > 0)
            {
                filtered = filtered.Where(s => s.Materi != null && s.Materi.MataKuliahId == selectedMatkulId);
            }

            if (selectedMateriId > 0)
            {
                filtered = filtered.Where(s => s.MateriId == selectedMateriId);
            }

            GridSoal.ItemsSource = filtered.ToList();
        }

        private void CmbFilterMatkul_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            int selectedMatkulId = (CmbFilterMatkul.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;

            // Rebuild Materi filter based on selected Matkul
            CmbFilterMateri.Items.Clear();
            CmbFilterMateri.Items.Add(new ComboBoxItem { Content = "-- Semua Topik / Materi --", Tag = 0 });

            var filteredMateri = selectedMatkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == selectedMatkulId)
                : _allMateri;

            foreach (var m in filteredMateri)
            {
                CmbFilterMateri.Items.Add(new ComboBoxItem { Content = $"P{m.Pertemuan}: {m.Judul}", Tag = m.Id });
            }
            CmbFilterMateri.SelectedIndex = 0;

            ApplyFilter();
        }

        private void CmbFilterMateri_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            ApplyFilter();
        }

        private void CmbFormMatkul_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            int selectedMatkulId = (CmbFormMatkul.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;

            CmbFormMateri.Items.Clear();
            var filteredMateri = selectedMatkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == selectedMatkulId)
                : _allMateri;

            foreach (var m in filteredMateri)
            {
                CmbFormMateri.Items.Add(new ComboBoxItem { Content = $"P{m.Pertemuan}: {m.Judul}", Tag = m.Id });
            }
            if (CmbFormMateri.Items.Count > 0) CmbFormMateri.SelectedIndex = 0;
        }

        private void TipeSoal_Changed(object sender, RoutedEventArgs e)
        {
            if (PanelPG == null || PanelEssay == null) return;
            if (RbPG.IsChecked == true)
            {
                PanelPG.Visibility = Visibility.Visible;
                PanelEssay.Visibility = Visibility.Collapsed;
            }
            else
            {
                PanelPG.Visibility = Visibility.Collapsed;
                PanelEssay.Visibility = Visibility.Visible;
            }
        }

        private void BtnPilihGambar_Click(object sender, RoutedEventArgs e)
        {
            var dlg = new OpenFileDialog
            {
                Title = "Pilih Gambar Diagram Soal (PNG / JPG)",
                Filter = "File Gambar (*.png;*.jpg;*.jpeg;*.bmp)|*.png;*.jpg;*.jpeg;*.bmp|Semua File (*.*)|*.*"
            };

            if (dlg.ShowDialog() == true)
            {
                try
                {
                    string ext = Path.GetExtension(dlg.FileName);
                    string destName = $"img_{DateTime.Now:yyyyMMdd_HHmmss}_{Guid.NewGuid().ToString("N")[..6]}{ext}";
                    string destPath = Path.Combine(DatabaseService.AttachmentsFolder, destName);
                    File.Copy(dlg.FileName, destPath, true);

                    _tempGambarPath = destPath;
                    TxtGambarPath.Text = $"🖼️ {Path.GetFileName(dlg.FileName)}";
                    TxtGambarPath.Foreground = System.Windows.Media.Brushes.LightGreen;
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Gagal menyalin gambar: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                }
            }
        }

        private void BtnHapusGambar_Click(object sender, RoutedEventArgs e)
        {
            _tempGambarPath = null;
            TxtGambarPath.Text = "Belum ada gambar";
            TxtGambarPath.Foreground = (System.Windows.Media.Brush)new System.Windows.Media.BrushConverter().ConvertFrom("#94A3B8")!;
        }


        private void BtnTambah_Click(object sender, RoutedEventArgs e)
        {
            ResetForm();
            TxtPertanyaan.Focus();
        }

        private void BtnRowEdit_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is Soal s)
            {
                _selectedSoal = s;
                TxtFormTitle.Text = $"Edit Soal #{s.Id}";

                // Set Matkul & Materi
                if (s.Materi != null)
                {
                    foreach (ComboBoxItem item in CmbFormMatkul.Items)
                    {
                        if (item.Tag is int mId && mId == s.Materi.MataKuliahId)
                        {
                            CmbFormMatkul.SelectedItem = item;
                            break;
                        }
                    }

                    foreach (ComboBoxItem item in CmbFormMateri.Items)
                    {
                        if (item.Tag is int tId && tId == s.MateriId)
                        {
                            CmbFormMateri.SelectedItem = item;
                            break;
                        }
                    }
                }

                TxtPertanyaan.Text = s.Pertanyaan;
                if (s.Tipe == TipeSoal.PilihanGanda)
                {
                    RbPG.IsChecked = true;
                    TxtPilihanA.Text = s.PilihanA;
                    TxtPilihanB.Text = s.PilihanB;
                    TxtPilihanC.Text = s.PilihanC;
                    TxtPilihanD.Text = s.PilihanD;
                    CmbJawabanBenar.SelectedIndex = Math.Clamp(s.JawabanBenar, 0, 3);
                }
                else
                {
                    RbEssay.IsChecked = true;
                    TxtKataKunci.Text = s.KataKunci;
                }

                // Media
                _tempGambarPath = s.GambarPath;
                if (!string.IsNullOrEmpty(s.GambarPath) && File.Exists(s.GambarPath))
                {
                    TxtGambarPath.Text = $"🖼️ {Path.GetFileName(s.GambarPath)}";
                    TxtGambarPath.Foreground = System.Windows.Media.Brushes.LightGreen;
                }
                else
                {
                    TxtGambarPath.Text = "Belum ada gambar";
                    TxtGambarPath.Foreground = (System.Windows.Media.Brush)new System.Windows.Media.BrushConverter().ConvertFrom("#94A3B8")!;
                }


                TxtPembahasan.Text = s.Pembahasan ?? "";
                BtnSimpan.Content = "Simpan Perubahan";
            }
        }

        private async void BtnRowDelete_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is Soal s)
            {
                var res = MessageBox.Show($"Hapus soal: \"{s.Pertanyaan.Substring(0, Math.Min(40, s.Pertanyaan.Length))}...\"?",
                    "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Warning);
                if (res == MessageBoxResult.Yes)
                {
                    await _db.DeleteSoalAsync(s.Id);
                    RefreshSoalList();
                }
            }
        }

        private async void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            string pertanyaan = TxtPertanyaan.Text.Trim();
            if (string.IsNullOrEmpty(pertanyaan))
            {
                MessageBox.Show("Pertanyaan wajib diisi!", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
                return;
            }

            int selectedMateriId = (CmbFormMateri.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;
            if (selectedMateriId == 0)
            {
                MessageBox.Show("Pilih materi terlebih dahulu!", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
                return;
            }

            bool isPG = RbPG.IsChecked == true;
            string pemb = TxtPembahasan.Text.Trim();

            try
            {
                if (_selectedSoal == null)
                {
                    var newSoal = new Soal
                    {
                        MateriId = selectedMateriId,
                        Pertanyaan = pertanyaan,
                        Tipe = isPG ? TipeSoal.PilihanGanda : TipeSoal.Essay,
                        PilihanA = isPG ? TxtPilihanA.Text.Trim() : null,
                        PilihanB = isPG ? TxtPilihanB.Text.Trim() : null,
                        PilihanC = isPG ? TxtPilihanC.Text.Trim() : null,
                        PilihanD = isPG ? TxtPilihanD.Text.Trim() : null,
                        JawabanBenar = isPG ? CmbJawabanBenar.SelectedIndex : 0,
                        KataKunci = !isPG ? TxtKataKunci.Text.Trim() : null,
                        GambarPath = _tempGambarPath,
                        GambarDesc = !string.IsNullOrEmpty(_tempGambarPath) ? Path.GetFileName(_tempGambarPath) : null,
                        Pembahasan = !string.IsNullOrEmpty(pemb) ? pemb : null
                    };

                    await _db.AddSoalAsync(newSoal);
                    MessageBox.Show("Soal baru berhasil ditambahkan!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }
                else
                {
                    _selectedSoal.MateriId = selectedMateriId;
                    _selectedSoal.Pertanyaan = pertanyaan;
                    _selectedSoal.Tipe = isPG ? TipeSoal.PilihanGanda : TipeSoal.Essay;
                    _selectedSoal.PilihanA = isPG ? TxtPilihanA.Text.Trim() : null;
                    _selectedSoal.PilihanB = isPG ? TxtPilihanB.Text.Trim() : null;
                    _selectedSoal.PilihanC = isPG ? TxtPilihanC.Text.Trim() : null;
                    _selectedSoal.PilihanD = isPG ? TxtPilihanD.Text.Trim() : null;
                    _selectedSoal.JawabanBenar = isPG ? CmbJawabanBenar.SelectedIndex : 0;
                    _selectedSoal.KataKunci = !isPG ? TxtKataKunci.Text.Trim() : null;
                    _selectedSoal.GambarPath = _tempGambarPath;
                    _selectedSoal.GambarDesc = !string.IsNullOrEmpty(_tempGambarPath) ? Path.GetFileName(_tempGambarPath) : null;
                    _selectedSoal.Pembahasan = !string.IsNullOrEmpty(pemb) ? pemb : null;

                    await _db.UpdateSoalAsync(_selectedSoal);
                    MessageBox.Show("Soal berhasil diperbarui!", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
                }

                RefreshSoalList();
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
            _selectedSoal = null;
            TxtFormTitle.Text = "Form Tambah Soal";
            TxtPertanyaan.Text = "";
            TxtPilihanA.Text = "";
            TxtPilihanB.Text = "";
            TxtPilihanC.Text = "";
            TxtPilihanD.Text = "";
            CmbJawabanBenar.SelectedIndex = 0;
            TxtKataKunci.Text = "";
            TxtPembahasan.Text = "";

            _tempGambarPath = null;
            TxtGambarPath.Text = "Belum ada gambar";
            TxtGambarPath.Foreground = (System.Windows.Media.Brush)new System.Windows.Media.BrushConverter().ConvertFrom("#94A3B8")!;



            RbPG.IsChecked = true;
            BtnSimpan.Content = "Simpan Soal";
        }
    }
}
