using System.Windows;

namespace AplikasiDesktop;

public partial class DashboardWindow : Window
{
    public DashboardWindow()
    {
        InitializeComponent();
    }

    private void tambah_data_click(object sender, RoutedEventArgs e)
    {
        TambahData windowTambah = new TambahData();
        windowTambah.ShowDialog();
    }

    private void cari_data_click(object sender, RoutedEventArgs e)
    {
        CariData windowCari = new CariData();
        windowCari.ShowDialog();
    }

    private void hapus_data_click(object sender, RoutedEventArgs e)
    {
        HapusData windowHapus = new HapusData();
        windowHapus.ShowDialog();
    }

    private void lihat_data_click(object sender, RoutedEventArgs e)
    {
        LihatData windowLihat = new LihatData();
        windowLihat.ShowDialog();
    }

    private void keluar_click(object sender, RoutedEventArgs e)
    {
        this.Close();
    }
}