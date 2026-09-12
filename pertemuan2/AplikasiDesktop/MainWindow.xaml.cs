using System.Text;
using System.Windows;


namespace AplikasiDesktop;

/// <summary>
/// Interaction logic for MainWindow.xaml
/// </summary>
public partial class MainWindow : Window
{
    public MainWindow()
    {
        InitializeComponent();
    }
    private void masuk_click(object sender, RoutedEventArgs e)
{
    DashboardWindow halamanBaru = new DashboardWindow();
   
    halamanBaru.Show();
    
    this.Close();
}
}