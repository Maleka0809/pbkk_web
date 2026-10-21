using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using BankSoalNET.Models;
using BankSoalNET.Services;

namespace BankSoalNET.Views
{
    public partial class ModeScoringView : UserControl
    {
        private readonly DatabaseService _db;
        private readonly User _currentUser;
        private List<MataKuliah> _matkulList = new();
        private List<Materi> _allMateri = new();
        private List<Soal> _currentSoals = new();

        // Control tracking maps
        private readonly Dictionary<int, RadioButton[]> _pgControls = new();
        private readonly Dictionary<int, TextBox> _essayControls = new();

        public ModeScoringView(DatabaseService db, User user)
        {
            InitializeComponent();
            _db = db;
            _currentUser = user;
            LoadInitialData();
        }

        public async void LoadInitialData()
        {
            _matkulList = await _db.GetAllMataKuliahAsync();
            _allMateri = await _db.GetAllMateriAsync();

            CmbMatkulScoring.Items.Clear();
            CmbMatkulScoring.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
            foreach (var mk in _matkulList)
            {
                CmbMatkulScoring.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan}", Tag = mk.Id });
            }
            CmbMatkulScoring.SelectedIndex = 0;

            RebuildMateriList(0);
        }

        private void CmbMatkulScoring_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            int selectedMatkulId = (CmbMatkulScoring.SelectedItem as ComboBoxItem)?.Tag is int mkId ? mkId : 0;
            RebuildMateriList(selectedMatkulId);
        }

        private void RebuildMateriList(int matkulId)
        {
            CmbMateriScoring.Items.Clear();
            var filtered = matkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == matkulId)
                : _allMateri;

            foreach (var m in filtered)
            {
                CmbMateriScoring.Items.Add(new ComboBoxItem { Content = $"P#{m.Pertemuan} - {m.Judul}", Tag = m.Id });
            }
            if (CmbMateriScoring.Items.Count > 0)
            {
                CmbMateriScoring.SelectedIndex = 0;
            }
            else
            {
                PanelQuestionsContainer.Children.Clear();
                BtnSubmitUjian.IsEnabled = false;
            }
        }

        private async void CmbMateriScoring_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (CmbMateriScoring.SelectedItem is ComboBoxItem item && item.Tag is int materiId)
            {
                PanelResult.Visibility = Visibility.Collapsed;
                ScrollQuiz.Visibility = Visibility.Visible;
                _currentSoals = await _db.GetSoalByMateriAsync(materiId);
                RenderQuestions();
            }
        }

        private void RenderQuestions()
        {
            PanelQuestionsContainer.Children.Clear();
            _pgControls.Clear();
            _essayControls.Clear();

            if (!_currentSoals.Any())
            {
                var emptyBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromRgb(30, 41, 59)),
                    CornerRadius = new CornerRadius(16),
                    Padding = new Thickness(24)
                };
                var emptyTxt = new TextBlock
                {
                    Text = "Belum ada soal pada materi ini. Silakan pilih materi lainnya.",
                    Foreground = new SolidColorBrush(Color.FromRgb(148, 163, 184)),
                    FontSize = 14,
                    HorizontalAlignment = HorizontalAlignment.Center
                };
                emptyBorder.Child = emptyTxt;
                PanelQuestionsContainer.Children.Add(emptyBorder);
                BtnSubmitUjian.IsEnabled = false;
                return;
            }

            BtnSubmitUjian.IsEnabled = true;

            int qNum = 1;
            foreach (var soal in _currentSoals)
            {
                var card = new Border
                {
                    Background = new SolidColorBrush(Color.FromRgb(30, 41, 59)),
                    CornerRadius = new CornerRadius(16),
                    Padding = new Thickness(20),
                    Margin = new Thickness(0, 0, 0, 16),
                    BorderBrush = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                    BorderThickness = new Thickness(1)
                };

                var cardStack = new StackPanel();

                // Header Soal
                var headerStack = new StackPanel { Orientation = Orientation.Horizontal, Margin = new Thickness(0, 0, 0, 10) };
                var badge = new Border
                {
                    Background = soal.Tipe == TipeSoal.PilihanGanda ? new SolidColorBrush(Color.FromRgb(16, 185, 129)) : new SolidColorBrush(Color.FromRgb(245, 158, 11)),
                    CornerRadius = new CornerRadius(6),
                    Padding = new Thickness(8, 2, 8, 2),
                    Margin = new Thickness(0, 0, 10, 0)
                };
                badge.Child = new TextBlock
                {
                    Text = $"Soal #{qNum} - {(soal.Tipe == TipeSoal.PilihanGanda ? "Pilihan Ganda" : "Essay")}",
                    FontSize = 11,
                    FontWeight = FontWeights.Bold,
                    Foreground = Brushes.White
                };
                headerStack.Children.Add(badge);
                cardStack.Children.Add(headerStack);

                // Pertanyaan Text
                var txtQuestion = new TextBlock
                {
                    Text = soal.Pertanyaan,
                    FontSize = 14,
                    FontWeight = FontWeights.SemiBold,
                    Foreground = Brushes.White,
                    TextWrapping = TextWrapping.Wrap,
                    Margin = new Thickness(0, 0, 0, 12)
                };
                cardStack.Children.Add(txtQuestion);

                // Media: Gambar Diagram
                if (soal.HasGambar && File.Exists(soal.GambarPath))
                {
                    var imgBox = new Border
                    {
                        Background = new SolidColorBrush(Color.FromRgb(15, 23, 42)),
                        CornerRadius = new CornerRadius(10),
                        Padding = new Thickness(10),
                        Margin = new Thickness(0, 0, 0, 12)
                    };
                    var iStack = new StackPanel();
                    try
                    {
                        var bi = new BitmapImage();
                        bi.BeginInit();
                        bi.UriSource = new Uri(soal.GambarPath);
                        bi.CacheOption = BitmapCacheOption.OnLoad;
                        bi.EndInit();
                        iStack.Children.Add(new Image { Source = bi, MaxHeight = 220, HorizontalAlignment = HorizontalAlignment.Left });
                    }
                    catch { }

                    var btnZoom = new Button
                    {
                        Content = "🔍 Perbesar Gambar",
                        Height = 28,
                        HorizontalAlignment = HorizontalAlignment.Left,
                        Margin = new Thickness(0, 6, 0, 0),
                        Background = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                        Foreground = Brushes.White,
                        BorderThickness = new Thickness(0)
                    };
                    btnZoom.Click += (o, e) =>
                    {
                        try { Process.Start(new ProcessStartInfo(soal.GambarPath!) { UseShellExecute = true }); } catch { }
                    };
                    iStack.Children.Add(btnZoom);
                    imgBox.Child = iStack;
                    cardStack.Children.Add(imgBox);
                }

                // Media: PDF Dokumen
                if (soal.HasPdf && File.Exists(soal.PdfPath))
                {
                    var pdfBar = new Border
                    {
                        Background = new SolidColorBrush(Color.FromArgb(40, 37, 99, 235)),
                        CornerRadius = new CornerRadius(8),
                        Padding = new Thickness(10, 8, 10, 8),
                        Margin = new Thickness(0, 0, 0, 12)
                    };
                    var pGrid = new Grid();
                    pGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
                    pGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = GridLength.Auto });

                    pGrid.Children.Add(new TextBlock
                    {
                        Text = $"📄 Lampiran Dokumen: {soal.PdfName ?? Path.GetFileName(soal.PdfPath)}",
                        Foreground = Brushes.White,
                        FontSize = 12,
                        VerticalAlignment = VerticalAlignment.Center
                    });

                    var btnOpen = new Button
                    {
                        Content = "Buka PDF",
                        Height = 26,
                        Padding = new Thickness(10, 0, 10, 0),
                        Background = new SolidColorBrush(Color.FromRgb(37, 99, 235)),
                        Foreground = Brushes.White,
                        BorderThickness = new Thickness(0)
                    };
                    btnOpen.Click += (o, e) =>
                    {
                        try { Process.Start(new ProcessStartInfo(soal.PdfPath!) { UseShellExecute = true }); } catch { }
                    };
                    Grid.SetColumn(btnOpen, 1);
                    pGrid.Children.Add(btnOpen);

                    pdfBar.Child = pGrid;
                    cardStack.Children.Add(pdfBar);
                }

                if (soal.Tipe == TipeSoal.PilihanGanda)
                {
                    string groupName = $"QGroup_{soal.Id}";
                    var rbs = new RadioButton[4];
                    string[] options = soal.GetPilihan();

                    for (int i = 0; i < 4; i++)
                    {
                        if (string.IsNullOrEmpty(options[i])) continue;

                        var rb = new RadioButton
                        {
                            Content = $"({(char)('A' + i)}) {options[i]}",
                            GroupName = groupName,
                            Foreground = new SolidColorBrush(Color.FromRgb(226, 232, 240)),
                            FontSize = 13,
                            Margin = new Thickness(0, 0, 0, 6)
                        };
                        rbs[i] = rb;
                        cardStack.Children.Add(rb);
                    }
                    _pgControls[soal.Id] = rbs;
                }
                else
                {
                    var txtEssay = new TextBox
                    {
                        Height = 70,
                        TextWrapping = TextWrapping.Wrap,
                        AcceptsReturn = true,
                        Background = new SolidColorBrush(Color.FromRgb(15, 23, 42)),
                        Foreground = Brushes.White,
                        BorderBrush = new SolidColorBrush(Color.FromRgb(71, 85, 105)),
                        Padding = new Thickness(8, 6, 8, 6)
                    };
                    _essayControls[soal.Id] = txtEssay;
                    cardStack.Children.Add(txtEssay);
                }

                card.Child = cardStack;
                PanelQuestionsContainer.Children.Add(card);
                qNum++;
            }
        }

        private async void BtnSubmitUjian_Click(object sender, RoutedEventArgs e)
        {
            if (!_currentSoals.Any()) return;

            int selectedMateriId = (CmbMateriScoring.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;
            var materi = _allMateri.FirstOrDefault(m => m.Id == selectedMateriId);

            int pgCount = 0;
            int pgBenar = 0;
            double essayTotalScore = 0;
            int essayCount = 0;

            foreach (var soal in _currentSoals)
            {
                if (soal.Tipe == TipeSoal.PilihanGanda)
                {
                    pgCount++;
                    if (_pgControls.TryGetValue(soal.Id, out var rbs))
                    {
                        for (int i = 0; i < 4; i++)
                        {
                            if (rbs[i] != null && rbs[i].IsChecked == true)
                            {
                                if (i == soal.JawabanBenar)
                                {
                                    pgBenar++;
                                }
                                break;
                            }
                        }
                    }
                }
                else
                {
                    essayCount++;
                    if (_essayControls.TryGetValue(soal.Id, out var txtBox))
                    {
                        string userText = txtBox.Text.ToLower();
                        string[] keywords = soal.GetKataKunci();
                        if (keywords.Length > 0)
                        {
                            int match = keywords.Count(k => userText.Contains(k.Trim().ToLower()));
                            double scorePct = (double)match / keywords.Length * 100.0;
                            essayTotalScore += scorePct;
                        }
                        else
                        {
                            essayTotalScore += string.IsNullOrWhiteSpace(userText) ? 0 : 100;
                        }
                    }
                }
            }

            int finalScore = 0;
            int pgScoreWeight = pgCount > 0 ? (int)((double)pgBenar / pgCount * 60) : 0;
            int essayAvg = essayCount > 0 ? (int)(essayTotalScore / essayCount) : 0;
            int essayScoreWeight = essayCount > 0 ? (int)(essayAvg * 0.4) : 0;

            if (pgCount > 0 && essayCount > 0)
            {
                finalScore = pgScoreWeight + essayScoreWeight;
            }
            else if (pgCount > 0)
            {
                finalScore = (int)((double)pgBenar / pgCount * 100);
            }
            else if (essayCount > 0)
            {
                finalScore = essayAvg;
            }

            var hasil = new HasilUjian
            {
                UserId = _currentUser.Id,
                MateriId = selectedMateriId,
                PgBenar = pgBenar,
                PgTotal = pgCount,
                EssaySkor = essayAvg,
                EssayTotal = essayCount,
                Nilai = finalScore,
                SubmittedAt = DateTime.Now
            };

            await _db.AddHasilAsync(hasil);

            // Display Results
            ScrollQuiz.Visibility = Visibility.Collapsed;
            PanelResult.Visibility = Visibility.Visible;

            TxtResultSubtitle.Text = $"Modul: {materi?.Judul ?? "Materi"}";
            TxtFinalScore.Text = finalScore.ToString();
            TxtPgScore.Text = pgCount > 0 ? $"{pgBenar} / {pgCount} Benar" : "Tidak ada PG";
            TxtEssayScore.Text = essayCount > 0 ? $"{essayAvg}% Kesesuaian Kata Kunci" : "Tidak ada Essay";

            if (finalScore >= 80)
            {
                TxtStatus.Text = "LULUS DENGAN PRESTASI SANGAT BAIK";
                BadgeStatus.Background = new SolidColorBrush(Color.FromRgb(6, 78, 59));
                TxtStatus.Foreground = new SolidColorBrush(Color.FromRgb(110, 231, 183));
            }
            else if (finalScore >= 60)
            {
                TxtStatus.Text = "LULUS — PERLU MEMPERDALAM MATERI";
                BadgeStatus.Background = new SolidColorBrush(Color.FromRgb(120, 53, 15));
                TxtStatus.Foreground = new SolidColorBrush(Color.FromRgb(253, 230, 138));
            }
            else
            {
                TxtStatus.Text = "BELUM LULUS — PELAJARI ULANG MODUL";
                BadgeStatus.Background = new SolidColorBrush(Color.FromRgb(127, 29, 29));
                TxtStatus.Foreground = new SolidColorBrush(Color.FromRgb(254, 202, 202));
            }
        }

        private void BtnUlangi_Click(object sender, RoutedEventArgs e)
        {
            PanelResult.Visibility = Visibility.Collapsed;
            ScrollQuiz.Visibility = Visibility.Visible;
            RenderQuestions();
        }
    }
}
