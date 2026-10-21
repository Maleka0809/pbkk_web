using System;
using System.Windows;
using System.Windows.Media;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class LoginWindow : Window
    {
        private readonly DatabaseService _db;

        public LoginWindow()
        {
            InitializeComponent();
            _db = new DatabaseService();
        }

        private void TabMode_Changed(object sender, RoutedEventArgs e)
        {
            if (PanelLogin == null || PanelRegister == null) return;

            if (TabLogin.IsChecked == true)
            {
                PanelLogin.Visibility = Visibility.Visible;
                PanelRegister.Visibility = Visibility.Collapsed;
            }
            else
            {
                PanelLogin.Visibility = Visibility.Collapsed;
                PanelRegister.Visibility = Visibility.Visible;
            }
        }

        private void SwitchToRegister_Click(object sender, RoutedEventArgs e)
        {
            TabRegister.IsChecked = true;
        }

        private void SwitchToLogin_Click(object sender, RoutedEventArgs e)
        {
            TabLogin.IsChecked = true;
        }

        private void FillAdmin_Click(object sender, RoutedEventArgs e)
        {
            TabLogin.IsChecked = true;
            TxtUsername.Text = "admin";
            TxtPassword.Password = "admin123";
        }

        private void FillMhs_Click(object sender, RoutedEventArgs e)
        {
            TabLogin.IsChecked = true;
            TxtUsername.Text = "mhs01";
            TxtPassword.Password = "mhs123";
        }

        private async void BtnLogin_Click(object sender, RoutedEventArgs e)
        {
            TxtError.Visibility = Visibility.Collapsed;
            string username = TxtUsername.Text.Trim();
            string password = TxtPassword.Password;

            if (string.IsNullOrEmpty(username) || string.IsNullOrEmpty(password))
            {
                TxtError.Text = "Harap masukkan username dan password.";
                TxtError.Visibility = Visibility.Visible;
                return;
            }

            BtnLogin.IsEnabled = false;
            BtnLogin.Content = "Memeriksa...";

            var user = await _db.LoginAsync(username, password);

            BtnLogin.IsEnabled = true;
            BtnLogin.Content = "Masuk ke Sistem";

            if (user == null)
            {
                TxtError.Text = "Username atau password salah.";
                TxtError.Visibility = Visibility.Visible;
                return;
            }

            // Otomatis membuka laman admin jika role admin, atau laman mhs jika role mhs
            var shell = new MainShell(user, _db);
            shell.Show();
            this.Close();
        }

        private async void BtnRegister_Click(object sender, RoutedEventArgs e)
        {
            TxtRegStatus.Visibility = Visibility.Collapsed;

            string nama = TxtRegNama.Text.Trim();
            string username = TxtRegUsername.Text.Trim();
            string password = TxtRegPassword.Password;
            string confirmPwd = TxtRegConfirmPassword.Password;
            string role = RbRegAdmin.IsChecked == true ? "admin" : "mahasiswa";

            if (string.IsNullOrWhiteSpace(nama) || string.IsNullOrWhiteSpace(username) ||
                string.IsNullOrWhiteSpace(password) || string.IsNullOrWhiteSpace(confirmPwd))
            {
                ShowRegisterStatus("Semua kolom pendaftaran wajib diisi!", isError: true);
                return;
            }

            if (username.Length < 3)
            {
                ShowRegisterStatus("Username minimal 3 karakter!", isError: true);
                return;
            }

            if (password.Length < 4)
            {
                ShowRegisterStatus("Password minimal 4 karakter!", isError: true);
                return;
            }

            if (password != confirmPwd)
            {
                ShowRegisterStatus("Konfirmasi password tidak cocok!", isError: true);
                return;
            }

            BtnRegister.IsEnabled = false;
            BtnRegister.Content = "Mendaftarkan...";

            var (success, message, newUser) = await _db.RegisterAsync(username, password, nama, role);

            BtnRegister.IsEnabled = true;
            BtnRegister.Content = "✨ Buat Akun Baru";

            if (!success || newUser == null)
            {
                ShowRegisterStatus(message, isError: true);
                return;
            }

            // Success
            var ask = MessageBox.Show(
                $"Akun '{newUser.NamaLengkap}' ({newUser.Role.ToUpper()}) berhasil didaftarkan!\n\nApakah Anda ingin langsung masuk ke aplikasi sekarang?",
                "Pendaftaran Berhasil",
                MessageBoxButton.YesNo,
                MessageBoxImage.Information);

            if (ask == MessageBoxResult.Yes)
            {
                var shell = new MainShell(newUser, _db);
                shell.Show();
                this.Close();
            }
            else
            {
                // Switch to login tab and prefill
                TabLogin.IsChecked = true;
                TxtUsername.Text = username;
                TxtPassword.Password = password;
                TxtError.Text = "Akun berhasil dibuat. Silakan klik 'Masuk ke Sistem'.";
                TxtError.Foreground = new SolidColorBrush(Color.FromRgb(52, 211, 153)); // Emerald green
                TxtError.Visibility = Visibility.Visible;

                // Clear register inputs
                TxtRegNama.Text = "";
                TxtRegUsername.Text = "";
                TxtRegPassword.Password = "";
                TxtRegConfirmPassword.Password = "";
            }
        }

        private void ShowRegisterStatus(string message, bool isError)
        {
            TxtRegStatus.Text = message;
            TxtRegStatus.Foreground = isError 
                ? new SolidColorBrush(Color.FromRgb(248, 113, 113)) 
                : new SolidColorBrush(Color.FromRgb(52, 211, 153));
            TxtRegStatus.Visibility = Visibility.Visible;
        }
    }
}
