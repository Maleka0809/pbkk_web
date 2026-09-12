using System;
using System.Windows;

namespace AplikasiDesktop;

public partial class HapusData : Window
{
    public HapusData()
    {
        InitializeComponent();
    }

    private void hapus_data_click(object sender, RoutedEventArgs e)
    {
        string nimHapus = InputNimHapus.Text;
        Mahasiswa? mahasiswaDitemukan = null;

        foreach (Mahasiswa m in DataStore.DaftarMahasiswa)
        {
            if (m.NIM.Equals(nimHapus, StringComparison.OrdinalIgnoreCase))
            {
                mahasiswaDitemukan = m;
                break;
            }
        }

        if (mahasiswaDitemukan != null)
        {
            DataStore.DaftarMahasiswa.Remove(mahasiswaDitemukan);
            MessageBox.Show("Data mahasiswa berhasil dihapus.");
            this.Close();
        }
        else
        {
            MessageBox.Show("Data mahasiswa tidak ditemukan.");
        }
    }
}
