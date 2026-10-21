using System.Windows.Controls;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class HasilMahasiswaView : UserControl
    {
        private readonly DatabaseService _db;

        public HasilMahasiswaView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
            LoadData();
        }

        public async void LoadData()
        {
            var list = await _db.GetAllHasilAsync();
            GridHasil.ItemsSource = list;
        }
    }
}
