using System.Linq;
using System.Windows.Controls;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class AdminDashboardView : UserControl
    {
        private readonly DatabaseService _db;

        public AdminDashboardView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
            LoadData();
        }

        public async void LoadData()
        {
            var matkuls = await _db.GetAllMataKuliahAsync();
            var materis = await _db.GetAllMateriAsync();
            var soals = await _db.GetAllSoalAsync();
            var hasils = await _db.GetAllHasilAsync();

            TxtTotalMatkul.Text = matkuls.Count.ToString();
            TxtTotalMateri.Text = materis.Count.ToString();
            TxtTotalPG.Text = soals.Count(s => s.Tipe == Models.TipeSoal.PilihanGanda).ToString();
            TxtTotalEssay.Text = soals.Count(s => s.Tipe == Models.TipeSoal.Essay).ToString();
            TxtTotalHasil.Text = hasils.Count.ToString();

            GridMateriOverview.ItemsSource = materis;
            GridRecentHasil.ItemsSource = hasils.Take(10).ToList();
        }
    }
}
