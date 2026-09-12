using System.Windows;

namespace AplikasiDesktop;

public partial class TambahData : Window
{
    public TambahData()
    {
        InitializeComponent();
    }
    
    private void tambah_data_click(object sender, RoutedEventArgs e)
    {
        string nim = InputNim.Text;
        string nama = InputNama.Text;
        string prodi = InputProdi.Text;
        
        if (double.TryParse(InputIpk.Text, out double ipk))
        {
            if (ipk >= 0 && ipk <= 4)
            {
                Mahasiswa mhsBaru = new Mahasiswa(nim, nama, prodi, ipk);
                DataStore.DaftarMahasiswa.Add(mhsBaru);
                
                MessageBox.Show("Data mahasiswa berhasil ditambahkan.");
                this.Close();
            }
            else
            {
                MessageBox.Show("IPK harus berupa angka 0 - 4.");
            }
        }
        else
        {
            MessageBox.Show("Format IPK salah! Gunakan angka.");
        }
    }
}