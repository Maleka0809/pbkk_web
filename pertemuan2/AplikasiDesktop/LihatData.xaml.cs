using System.Windows;

namespace AplikasiDesktop;

public partial class LihatData : Window
{
    public LihatData()
    {
        InitializeComponent();
        LoadData();
    }

    private void LoadData()
    {
        TabelMahasiswa.ItemsSource = null;
        TabelMahasiswa.ItemsSource = DataStore.DaftarMahasiswa;
    }

    private void tutup_click(object sender, RoutedEventArgs e)
    {
        this.Close();
    }
}
