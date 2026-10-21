using BankSoalNET.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using BankSoalNET.Services;
using System.Threading.Tasks;
using System.Windows;

namespace BankSoalNET.ViewModels
{
    public partial class LoginViewModel : ObservableObject
    {
        private readonly DatabaseService _db;

        [ObservableProperty] private string _username = string.Empty;
        [ObservableProperty] private string _password = string.Empty;
        [ObservableProperty] private string _selectedRole = "admin";
        [ObservableProperty] private string _errorMessage = string.Empty;
        [ObservableProperty] private bool _isLoading = false;

        public LoginViewModel(DatabaseService db) => _db = db;

        [RelayCommand]
        private async Task LoginAsync()
        {
            ErrorMessage = string.Empty;
            if (string.IsNullOrWhiteSpace(Username) || string.IsNullOrWhiteSpace(Password))
            {
                ErrorMessage = "Username dan password tidak boleh kosong.";
                return;
            }

            IsLoading = true;
            var user = await _db.LoginAsync(Username, Password, SelectedRole);
            IsLoading = false;

            if (user == null)
            {
                ErrorMessage = "Username atau password salah. Silakan coba lagi.";
                return;
            }

            // Open main window
            var mainWindow = new Views.MainShell(user);
            mainWindow.Show();
            Application.Current.Windows[0]?.Close();
        }
    }
}
