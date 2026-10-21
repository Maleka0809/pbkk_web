using System.Windows.Controls;
using BankSoalNET.Models;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class RiwayatNilaiView : UserControl
    {
        private readonly DatabaseService _db;
        private readonly User _currentUser;

        public RiwayatNilaiView(DatabaseService db, User user)
        {
            InitializeComponent();
            _db = db;
            _currentUser = user;
            LoadData();
        }

        public async void LoadData()
        {
            var list = await _db.GetHasilByUserAsync(_currentUser.Id);
            GridRiwayat.ItemsSource = list;
        }
    }
}
