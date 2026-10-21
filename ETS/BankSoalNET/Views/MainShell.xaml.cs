using System.Windows;
using BankSoalNET.Models;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class MainShell : Window
    {
        private readonly User _user;
        private readonly DatabaseService _db;

        // Cached views
        private AdminDashboardView? _adminDashboard;
        private KelolaMatkulView? _kelolaMatkul;
        private KelolaMateriView? _kelolaMateri;
        private KelolaSoalView? _kelolaSoal;
        private BankSoalView? _bankSoal;
        private HasilMahasiswaView? _hasilMahasiswa;

        private ModeBelajarView? _modeBelajar;
        private ModeScoringView? _modeScoring;
        private RiwayatNilaiView? _riwayatNilai;
        private DokumenPdfView? _dokumenPdf;

        public MainShell(User user, DatabaseService? db = null)
        {
            InitializeComponent();
            _user = user;
            _db = db ?? new DatabaseService();

            SetupUI();
        }

        private void SetupUI()
        {
            TxtNamaUser.Text = _user.NamaLengkap;
            TxtUsername.Text = $"@{ _user.Username }";
            TxtAvatarInitials.Text = string.IsNullOrEmpty(_user.NamaLengkap) ? "U" : _user.NamaLengkap.Substring(0, 2).ToUpper();

            if (_user.Role.ToLower() == "admin")
            {
                TxtRoleBadge.Text = "ROLE: ADMIN";
                MenuAdmin.Visibility = Visibility.Visible;
                MenuMahasiswa.Visibility = Visibility.Collapsed;
                NavAdminDashboard_Click(this, new RoutedEventArgs());
            }
            else
            {
                TxtRoleBadge.Text = "ROLE: MAHASISWA";
                MenuAdmin.Visibility = Visibility.Collapsed;
                MenuMahasiswa.Visibility = Visibility.Visible;
                NavModeBelajar_Click(this, new RoutedEventArgs());
            }
        }

        // Admin Navigation
        private void NavAdminDashboard_Click(object sender, RoutedEventArgs e)
        {
            _adminDashboard ??= new AdminDashboardView(_db);
            _adminDashboard.LoadData();
            MainContentArea.Content = _adminDashboard;
        }

        private void NavKelolaMatkul_Click(object sender, RoutedEventArgs e)
        {
            _kelolaMatkul ??= new KelolaMatkulView(_db);
            _kelolaMatkul.LoadData();
            MainContentArea.Content = _kelolaMatkul;
        }

        private void NavKelolaMateri_Click(object sender, RoutedEventArgs e)
        {
            _kelolaMateri ??= new KelolaMateriView(_db);
            _kelolaMateri.LoadData();
            MainContentArea.Content = _kelolaMateri;
        }

        private void NavKelolaSoal_Click(object sender, RoutedEventArgs e)
        {
            _kelolaSoal ??= new KelolaSoalView(_db);
            _kelolaSoal.LoadInitialData();
            MainContentArea.Content = _kelolaSoal;
        }

        private void NavBankSoal_Click(object sender, RoutedEventArgs e)
        {
            _bankSoal ??= new BankSoalView(_db);
            _bankSoal.LoadData();
            MainContentArea.Content = _bankSoal;
        }

        private void NavHasilMahasiswa_Click(object sender, RoutedEventArgs e)
        {
            _hasilMahasiswa ??= new HasilMahasiswaView(_db);
            _hasilMahasiswa.LoadData();
            MainContentArea.Content = _hasilMahasiswa;
        }

        private async void NavDokumenPdf_Click(object sender, RoutedEventArgs e)
        {
            _dokumenPdf ??= new DokumenPdfView(_db, _user);
            await _dokumenPdf.LoadDataAsync();
            MainContentArea.Content = _dokumenPdf;
        }

        // Mahasiswa Navigation
        private void NavModeBelajar_Click(object sender, RoutedEventArgs e)
        {
            _modeBelajar ??= new ModeBelajarView(_db);
            _modeBelajar.LoadMateris();
            MainContentArea.Content = _modeBelajar;
        }

        private void NavModeScoring_Click(object sender, RoutedEventArgs e)
        {
            _modeScoring = new ModeScoringView(_db, _user);
            MainContentArea.Content = _modeScoring;
        }

        private void NavRiwayatNilai_Click(object sender, RoutedEventArgs e)
        {
            _riwayatNilai ??= new RiwayatNilaiView(_db, _user);
            _riwayatNilai.LoadData();
            MainContentArea.Content = _riwayatNilai;
        }

        private void BtnLogout_Click(object sender, RoutedEventArgs e)
        {
            var login = new LoginWindow();
            login.Show();
            this.Close();
        }

        public void ShowAdminDashboard() => NavAdminDashboard_Click(this, new RoutedEventArgs());
        public void ShowKelolaMatkul() => NavKelolaMatkul_Click(this, new RoutedEventArgs());
        public void ShowKelolaMateri() => NavKelolaMateri_Click(this, new RoutedEventArgs());
        public void ShowKelolaSoal() => NavKelolaSoal_Click(this, new RoutedEventArgs());
        public void ShowBankSoal() => NavBankSoal_Click(this, new RoutedEventArgs());
        public void ShowHasilMahasiswa() => NavHasilMahasiswa_Click(this, new RoutedEventArgs());
        public void ShowDokumenPdf() => NavDokumenPdf_Click(this, new RoutedEventArgs());

        public void ShowModeBelajar() => NavModeBelajar_Click(this, new RoutedEventArgs());
        public void ShowModeScoring() => NavModeScoring_Click(this, new RoutedEventArgs());
        public void ShowRiwayatNilai() => NavRiwayatNilai_Click(this, new RoutedEventArgs());
    }
}
