using System;
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
        string nimCari = InputNimCari.Text;
        Mahasiswa? mahasiswaDitemukan = null;

        foreach (Mahasiswa m in DataStore.DaftarMahasiswa)
        {
            if (m.NIM.Equals(nimCari, StringComparison.OrdinalIgnoreCase))
            {
                mahasiswaDitemukan = m;
                break;
            }
        }

        if (mahasiswaDitemukan != null)
        {
            MessageBox.Show($"Data Ditemukan!\n\nNIM: {mahasiswaDitemukan.NIM}\nNama: {mahasiswaDitemukan.Nama}\nProdi: {mahasiswaDitemukan.Prodi}\nIPK: {mahasiswaDitemukan.IPK}");
            this.Close();
        }
        else
        {
            MessageBox.Show("Mahasiswa dengan NIM tersebut tidak ditemukan.");
        }
    }
}
