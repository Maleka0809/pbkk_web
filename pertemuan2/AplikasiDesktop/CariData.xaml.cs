using System;
using System.Collections.Generic;
using System.Windows;

namespace AplikasiDesktop;

public partial class CariData : Window
{
    public CariData()
    {
        InitializeComponent();
    }

    private void cari_data_click(object sender, RoutedEventArgs e)
    {
        string keyword = InputPencarian.Text.ToLower();
        List<Mahasiswa> hasilPencarian = new List<Mahasiswa>();

        foreach (Mahasiswa m in DataStore.DaftarMahasiswa)
        {
            if (m.NIM.ToLower().Contains(keyword) || m.Nama.ToLower().Contains(keyword))
            {
                hasilPencarian.Add(m);
            }
        }

        TabelHasilPencarian.ItemsSource = null;
        
        if (hasilPencarian.Count > 0)
        {
            TabelHasilPencarian.ItemsSource = hasilPencarian;
        }
        else
        {
            MessageBox.Show("Mahasiswa tidak ditemukan.");
        }
    }
}
