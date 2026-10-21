using System.Collections.Generic;
using System.Linq;
using System.Windows.Controls;
using BankSoalNET.Models;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class ModeBelajarView : UserControl
    {
        private readonly DatabaseService _db;
        private List<MataKuliah> _matkulList = new();
        private List<Materi> _allMateri = new();

        public ModeBelajarView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
            LoadMateris();
        }

        public async void LoadMateris()
        {
            _matkulList = await _db.GetAllMataKuliahAsync();
            _allMateri = await _db.GetAllMateriAsync();

            CmbMatkul.Items.Clear();
            CmbMatkul.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
            foreach (var mk in _matkulList)
            {
                CmbMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan}", Tag = mk.Id });
            }
            CmbMatkul.SelectedIndex = 0;

            FilterMateri();
        }

        private void CmbMatkul_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            FilterMateri();
        }

        private void FilterMateri()
        {
            int selectedMatkulId = (CmbMatkul.SelectedItem as ComboBoxItem)?.Tag is int mkId ? mkId : 0;
            var list = selectedMatkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == selectedMatkulId).ToList()
                : _allMateri;

            ListMateriCards.ItemsSource = list;
            if (list.Any())
            {
                ListMateriCards.SelectedIndex = 0;
            }
            else
            {
                TxtJudul.Text = "Belum Ada Materi";
                TxtDeskripsi.Text = "Pilih mata kuliah lain atau tunggu admin menambahkan modul.";
                TxtKonten.Text = "";
                ItemsLatihanSoal.ItemsSource = null;
            }
        }

        private async void ListMateriCards_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (ListMateriCards.SelectedItem is Materi m)
            {
                TxtPertemuan.Text = $"Pertemuan {m.Pertemuan}";
                TxtTags.Text = m.Tags;
                TxtJudul.Text = m.Judul;
                TxtDeskripsi.Text = m.Deskripsi;
                TxtKonten.Text = m.Konten;

                var soals = await _db.GetSoalByMateriAsync(m.Id);
                ItemsLatihanSoal.ItemsSource = soals;
            }
        }
    }
}
