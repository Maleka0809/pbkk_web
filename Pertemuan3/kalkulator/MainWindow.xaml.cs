using System;
using System.Windows;

namespace kalkulator;

public partial class MainWindow : Window
{
    private double bil1;
    private double bil2;
    private string op = "";
    private bool operasiklik = false;
    private bool modeScientific = false; 

    public MainWindow()
    {
        InitializeComponent();
    }

    private void masuk_angka(string angka)
    {
        if(Layar.Text == "0" || operasiklik)
        {
            Layar.Text = angka;
            operasiklik = false;    
        }
        else
        {
            Layar.Text += angka;
        }
    }

    private void aturoperasi(string operasi)
    {
        bil1 = double.Parse(Layar.Text);
        op = operasi;
        operasiklik = true;
    }

    private void bc_1(object sender, RoutedEventArgs e) => masuk_angka("1");
    private void bc_2(object sender, RoutedEventArgs e) => masuk_angka("2");
    private void bc_3(object sender, RoutedEventArgs e) => masuk_angka("3");
    private void bc_4(object sender, RoutedEventArgs e) => masuk_angka("4");
    private void bc_5(object sender, RoutedEventArgs e) => masuk_angka("5");
    private void bc_6(object sender, RoutedEventArgs e) => masuk_angka("6");
    private void bc_7(object sender, RoutedEventArgs e) => masuk_angka("7");
    private void bc_8(object sender, RoutedEventArgs e) => masuk_angka("8");
    private void bc_9(object sender, RoutedEventArgs e) => masuk_angka("9");
    private void bc_0(object sender, RoutedEventArgs e) => masuk_angka("0");
    private void bc_dot(object sender, RoutedEventArgs e) => masuk_angka(".");

    private void bc_plus(object sender, RoutedEventArgs e) => aturoperasi("+");
    private void bc_minus(object sender, RoutedEventArgs e) => aturoperasi("-");
    private void bc_multiply(object sender, RoutedEventArgs e) => aturoperasi("*");
    private void bc_divide(object sender, RoutedEventArgs e) => aturoperasi("/");

    private void bc_hitung(object sender, RoutedEventArgs e)
    {
        bil2 = double.Parse(Layar.Text);
        double hasil = 0;

        switch (op)
        {
            case "+": hasil = bil1 + bil2; break;
            case "-": hasil = bil1 - bil2; break;
            case "*": hasil = bil1 * bil2; break;
            case "/": 
                if (bil2 == 0) { Layar.Text = "Error"; return; }
                hasil = bil1 / bil2; 
                break;
        }

        if (modeScientific && hasil % 1 != 0)
        {
            Layar.Text = UbahKePecahan(hasil);
        }
        else
        {
            Layar.Text = hasil.ToString();
        }
        
        op = "";
    }

    private void bc_ac(object sender, RoutedEventArgs e)
    {
        bil1 = 0;
        op = "";
        Layar.Text = "0";
    }

    private void bc_sci(object sender, RoutedEventArgs e)
    {
        modeScientific = true;
        MessageBox.Show("Mode Scientific Aktif: Hasil desimal akan menjadi pecahan.");
    }

    private void bc_def(object sender, RoutedEventArgs e)
    {
        modeScientific = false;
        MessageBox.Show("Mode Default Aktif: Hasil berupa angka desimal biasa.");
    }

    private string UbahKePecahan(double angka)
    {
        int tanda = Math.Sign(angka);
        angka = Math.Abs(angka);

        string teksAngka = angka.ToString(System.Globalization.CultureInfo.InvariantCulture);
        int jumlahDesimal = teksAngka.Contains(".") ? teksAngka.Length - teksAngka.IndexOf('.') - 1 : 0;
        if (jumlahDesimal > 5) jumlahDesimal = 5; 

        long penyebut = (long)Math.Pow(10, jumlahDesimal);
        long pembilang = (long)Math.Round(angka * penyebut);

        long fpb = CariFPB(pembilang, penyebut);

        long pembilangAkhir = (pembilang / fpb) * tanda;
        long penyebutAkhir = penyebut / fpb;

        return $"{pembilangAkhir}/{penyebutAkhir}";
    }

    private long CariFPB(long a, long b)
    {
        while (b != 0)
        {
            long sisa = a % b;
            a = b;
            b = sisa;
        }
        return a;
    }
}