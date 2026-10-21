using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using BankSoalNET.Models;
using BankSoalNET.Services;
using Microsoft.Win32;

namespace BankSoalNET.Views
{
    public partial class DokumenPdfView : UserControl
    {
        private readonly DatabaseService _db;
        private readonly User _currentUser;
        private List<DokumenSoal> _allDocs = new();
        private List<MataKuliah> _matkulList = new();
        private DokumenSoal? _selectedDoc;



        public Visibility AdminActionVisibility => 
            _currentUser.Role == "admin" ? Visibility.Visible : Visibility.Collapsed;

        public DokumenPdfView(DatabaseService db, User currentUser)
        {
            InitializeComponent();
            _db = db;
            _currentUser = currentUser;
            this.DataContext = this;

            BtnUploadPdf.Visibility = AdminActionVisibility;

            Loaded += async (s, e) => await LoadDataAsync();
        }

        public async Task LoadDataAsync()
        {
            try
            {
                _matkulList = await _db.GetAllMataKuliahAsync();

                // Setup Filter Dropdown
                CbFilterMatkul.Items.Clear();
                CbFilterMatkul.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
                foreach (var mk in _matkulList)
                {
                    CbFilterMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Kode} - {mk.Singkatan}", Tag = mk.Id });
                }
                CbFilterMatkul.SelectedIndex = 0;


                // Load Documents
                _allDocs = await _db.GetAllDokumenSoalAsync();
                ApplyFilter();

                // Auto-select first doc for immediate reading if available
                if (_allDocs.Any() && _selectedDoc == null)
                {
                    await SelectDocumentAsync(_allDocs.First());
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal memuat dokumen PDF: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void ApplyFilter()
        {
            int selectedMatkulId = 0;
            if (CbFilterMatkul.SelectedItem is ComboBoxItem item && item.Tag is int id)
            {
                selectedMatkulId = id;
            }

            string search = TxtCariDoc?.Text?.Trim().ToLower() ?? "";

            var filtered = _allDocs.AsEnumerable();

            if (selectedMatkulId > 0)
            {
                filtered = filtered.Where(d => d.MataKuliahId == selectedMatkulId);
            }

            if (!string.IsNullOrEmpty(search))
            {
                filtered = filtered.Where(d =>
                    (d.Judul ?? "").ToLower().Contains(search) ||
                    (d.Deskripsi ?? "").ToLower().Contains(search) ||
                    (d.MatkulNama ?? "").ToLower().Contains(search));
            }

            var list = filtered.ToList();
            ItemsDokumen.ItemsSource = list;
            TxtTotalDoc.Text = $"{list.Count} Dokumen";
        }

        private void CbFilterMatkul_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            ApplyFilter();
        }

        private void TxtCariDoc_TextChanged(object sender, TextChangedEventArgs e)
        {
            ApplyFilter();
        }

        private async void DokumenCard_Click(object sender, System.Windows.Input.MouseButtonEventArgs e)
        {
            if (sender is FrameworkElement elem && elem.Tag is DokumenSoal doc)
            {
                await SelectDocumentAsync(doc);
            }
        }

        private async Task SelectDocumentAsync(DokumenSoal doc)
        {
            _selectedDoc = doc;
            TxtActiveTitle.Text = doc.Judul;
            TxtActiveMatkul.Text = doc.MatkulNama;
            TxtActiveDesc.Text = !string.IsNullOrWhiteSpace(doc.Deskripsi) ? doc.Deskripsi : $"Berkas: {doc.PdfName} ({doc.UkuranFile})";
            PanelEmptyState.Visibility = Visibility.Collapsed;

            try
            {
                await PdfWebView.EnsureCoreWebView2Async();
                if (File.Exists(doc.PdfPath))
                {
                    PdfWebView.CoreWebView2?.Navigate(new Uri(doc.PdfPath).AbsoluteUri);
                }
                else
                {
                    MessageBox.Show("Berkas fisik PDF tidak ditemukan di disk.", "Info", MessageBoxButton.OK, MessageBoxImage.Warning);
                }
            }
            catch
            {
                // If WebView2 runtime has an issue initializing, external viewer button remains active
            }
        }

        private void BtnOpenExternal_Click(object sender, RoutedEventArgs e)
        {
            if (_selectedDoc == null || string.IsNullOrEmpty(_selectedDoc.PdfPath))
            {
                MessageBox.Show("Pilih dokumen terlebih dahulu.", "Info", MessageBoxButton.OK, MessageBoxImage.Information);
                return;
            }

            if (File.Exists(_selectedDoc.PdfPath))
            {
                try
                {
                    Process.Start(new ProcessStartInfo(_selectedDoc.PdfPath) { UseShellExecute = true });
                }
                catch (Exception ex)
                {
                    MessageBox.Show($"Tidak dapat membuka file PDF: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
                }
            }
            else
            {
                MessageBox.Show("File PDF tidak ditemukan di lokasi penyimpanan.", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
            }
        }

        private async void BtnDeleteDoc_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button btn && btn.Tag is DokumenSoal doc)
            {
                var ask = MessageBox.Show($"Apakah Anda yakin ingin menghapus dokumen:\n\"{doc.Judul}\"?",
                    "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Warning);

                if (ask == MessageBoxResult.Yes)
                {
                    await _db.DeleteDokumenSoalAsync(doc.Id);
                    if (_selectedDoc?.Id == doc.Id)
                    {
                        _selectedDoc = null;
                        PanelEmptyState.Visibility = Visibility.Visible;
                        TxtActiveTitle.Text = "Pilih dokumen PDF untuk mulai membaca";
                        TxtActiveMatkul.Text = "Semua";
                        TxtActiveDesc.Text = "Viewer PDF interaktif dengan navigasi zoom, cetak, pencarian kata, dan halaman.";
                    }
                    await LoadDataAsync();
                }
            }
        }

        // ================= MODAL UPLOAD =================
        private async void BtnUploadPdf_Click(object sender, RoutedEventArgs e)
        {
            var win = Window.GetWindow(this);
            var dlg = new UploadPdfWindow(_db, _matkulList)
            {
                Owner = win
            };

            if (dlg.ShowDialog() == true && dlg.UploadedDoc != null)
            {
                await LoadDataAsync();
                await SelectDocumentAsync(dlg.UploadedDoc);
            }
        }
    }
}
