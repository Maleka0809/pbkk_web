using System;
using System.Collections.Generic;
using System.IO;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using BankSoalNET.Models;
using BankSoalNET.Services;
using Microsoft.Win32;

namespace BankSoalNET.Views
{
    public partial class UploadPdfWindow : Window
    {
        private readonly DatabaseService _db;
        private readonly List<MataKuliah> _matkulList;

        private string? _tempUploadPath;
        private string? _tempUploadName;
        private string? _tempUploadSize;

        public DokumenSoal? UploadedDoc { get; private set; }

        public UploadPdfWindow(DatabaseService db, List<MataKuliah> matkulList)
        {
            InitializeComponent();
            _db = db;
            _matkulList = matkulList;

            // Populate Mata Kuliah ComboBox
            CbUploadMatkul.Items.Clear();
            foreach (var mk in _matkulList)
            {
                CbUploadMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Kode} - {mk.Nama}", Tag = mk.Id });
            }
            if (CbUploadMatkul.Items.Count > 0) CbUploadMatkul.SelectedIndex = 0;
        }

        private void Header_MouseLeftButtonDown(object sender, MouseButtonEventArgs e)
        {
            if (e.ButtonState == MouseButtonState.Pressed)
            {
                this.DragMove();
            }
        }

        private void BtnCancel_Click(object sender, RoutedEventArgs e)
        {
            this.DialogResult = false;
            this.Close();
        }

        private void BtnBrowsePdf_Click(object sender, RoutedEventArgs e)
        {
            var dlg = new OpenFileDialog
            {
                Title = "Pilih File Dokumen Bank Soal (PDF)",
                Filter = "File PDF (*.pdf)|*.pdf|Semua Berkas (*.*)|*.*"
            };

            if (dlg.ShowDialog() == true)
            {
                try
                {
                    var fileInfo = new FileInfo(dlg.FileName);
                    double sizeKb = fileInfo.Length / 1024.0;
                    string sizeStr = sizeKb >= 1024 ? $"{sizeKb / 1024.0:F1} MB" : $"{sizeKb:F0} KB";

                    string destName = $"doc_{DateTime.Now:yyyyMMdd_HHmmss}_{Path.GetFileName(dlg.FileName)}";
                    string destPath = Path.Combine(DatabaseService.AttachmentsFolder, destName);
                    File.Copy(dlg.FileName, destPath, true);

                    _tempUploadPath = destPath;
                    _tempUploadName = Path.GetFileName(dlg.FileName);
                    _tempUploadSize = sizeStr;

                    TxtUploadFileName.Text = $"📄 {_tempUploadName}";
                    TxtUploadFileSize.Text = $"Ukuran: {sizeStr}";
                    TxtUploadError.Visibility = Visibility.Collapsed;
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Gagal membaca berkas PDF: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                }
            }
        }

        private async void BtnSaveUpload_Click(object sender, RoutedEventArgs e)
        {
            string judul = TxtUploadJudul.Text.Trim();
            string desc = TxtUploadDesc.Text.Trim();

            if (string.IsNullOrEmpty(judul))
            {
                ShowError("Judul dokumen wajib diisi!");
                return;
            }

            if (CbUploadMatkul.SelectedItem is not ComboBoxItem item || item.Tag is not int matkulId || matkulId == 0)
            {
                ShowError("Pilih mata kuliah untuk dokumen ini!");
                return;
            }

            if (string.IsNullOrEmpty(_tempUploadPath) || !File.Exists(_tempUploadPath))
            {
                ShowError("Silakan pilih file dokumen PDF terlebih dahulu!");
                return;
            }

            try
            {
                BtnSaveUpload.IsEnabled = false;
                BtnSaveUpload.Content = "Menyimpan...";

                var newDoc = new DokumenSoal
                {
                    MataKuliahId = matkulId,
                    Judul = judul,
                    Deskripsi = desc,
                    PdfPath = _tempUploadPath,
                    PdfName = _tempUploadName ?? Path.GetFileName(_tempUploadPath),
                    UkuranFile = _tempUploadSize ?? "100 KB",
                    TanggalUpload = DateTime.Now
                };

                await _db.AddDokumenSoalAsync(newDoc);
                UploadedDoc = newDoc;

                this.DialogResult = true;
                this.Close();
            }
            catch (Exception ex)
            {
                BtnSaveUpload.IsEnabled = true;
                BtnSaveUpload.Content = "✨ Simpan & Publikasi PDF";
                ShowError($"Gagal menyimpan: {ex.Message}");
            }
        }

        private void ShowError(string msg)
        {
            TxtUploadError.Text = msg;
            TxtUploadError.Visibility = Visibility.Visible;
        }
    }
}
