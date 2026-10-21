using BankSoalNET.Models;
using BankSoalNET.Services;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Controls.Primitives;
using System.Windows.Media;
using System.Windows.Media.Imaging;

namespace BankSoalNET.Views
{
    public partial class BankSoalView : UserControl
    {
        private readonly DatabaseService _db;
        private List<MataKuliah> _matkulList = new();
        private List<Materi> _allMateri = new();
        private List<Soal> _allSoal = new();
        private bool _showKunci = true;

        public BankSoalView(DatabaseService db)
        {
            InitializeComponent();
            _db = db;
        }

        public async void LoadData()
        {
            try
            {
                _matkulList = await _db.GetAllMataKuliahAsync();
                _allMateri = await _db.GetAllMateriAsync();
                _allSoal = await _db.GetAllSoalAsync();

                // Setup CmbMatkul
                CmbMatkul.Items.Clear();
                CmbMatkul.Items.Add(new ComboBoxItem { Content = "-- Semua Mata Kuliah --", Tag = 0 });
                foreach (var mk in _matkulList)
                {
                    CmbMatkul.Items.Add(new ComboBoxItem { Content = $"{mk.Icon} {mk.Singkatan}", Tag = mk.Id });
                }
                CmbMatkul.SelectedIndex = 0;

                // Setup CmbMateri
                RebuildMateriFilter(0);

                RenderCards();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Gagal memuat bank soal: {ex.Message}", "Error", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void RebuildMateriFilter(int matkulId)
        {
            CmbMateri.Items.Clear();
            CmbMateri.Items.Add(new ComboBoxItem { Content = "-- Semua Topik / Materi --", Tag = 0 });

            var filtered = matkulId > 0
                ? _allMateri.Where(m => m.MataKuliahId == matkulId)
                : _allMateri;

            foreach (var m in filtered)
            {
                CmbMateri.Items.Add(new ComboBoxItem { Content = $"P{m.Pertemuan}: {m.Judul}", Tag = m.Id });
            }
            CmbMateri.SelectedIndex = 0;
        }

        private void FilterChanged(object sender, SelectionChangedEventArgs e)
        {
            if (sender == CmbMatkul)
            {
                int matkulId = (CmbMatkul.SelectedItem as ComboBoxItem)?.Tag is int mId ? mId : 0;
                RebuildMateriFilter(matkulId);
            }
            RenderCards();
        }

        private void TxtSearch_TextChanged(object sender, TextChangedEventArgs e)
        {
            RenderCards();
        }

        private void ToggleKunci_Changed(object sender, RoutedEventArgs e)
        {
            _showKunci = ToggleKunci.IsChecked == true;
            if (TxtToggleStatus != null)
            {
                TxtToggleStatus.Text = _showKunci ? "ON" : "OFF";
                TxtToggleStatus.Foreground = _showKunci
                    ? new SolidColorBrush(Color.FromRgb(52, 211, 153))
                    : new SolidColorBrush(Color.FromRgb(248, 113, 113));
            }
            RenderCards();
        }

        private void RenderCards()
        {
            if (PanelCardsContainer == null) return;
            PanelCardsContainer.Children.Clear();

            int matkulId = (CmbMatkul.SelectedItem as ComboBoxItem)?.Tag is int mkId ? mkId : 0;
            int materiId = (CmbMateri.SelectedItem as ComboBoxItem)?.Tag is int mtId ? mtId : 0;
            string tipeFilter = (CmbTipe.SelectedItem as ComboBoxItem)?.Tag?.ToString() ?? "all";
            string search = TxtSearch.Text.Trim().ToLower();

            var filtered = _allSoal.AsEnumerable();

            if (matkulId > 0)
            {
                filtered = filtered.Where(s => s.Materi != null && s.Materi.MataKuliahId == matkulId);
            }

            if (materiId > 0)
            {
                filtered = filtered.Where(s => s.MateriId == materiId);
            }

            if (tipeFilter == "pg") filtered = filtered.Where(s => s.Tipe == TipeSoal.PilihanGanda);
            else if (tipeFilter == "essay") filtered = filtered.Where(s => s.Tipe == TipeSoal.Essay);
            else if (tipeFilter == "gambar") filtered = filtered.Where(s => s.HasGambar);
            else if (tipeFilter == "pdf") filtered = filtered.Where(s => s.HasPdf);

            if (!string.IsNullOrEmpty(search))
            {
                filtered = filtered.Where(s =>
                    s.Pertanyaan.ToLower().Contains(search) ||
                    (s.PilihanA != null && s.PilihanA.ToLower().Contains(search)) ||
                    (s.PilihanB != null && s.PilihanB.ToLower().Contains(search)) ||
                    (s.KataKunci != null && s.KataKunci.ToLower().Contains(search)) ||
                    (s.Pembahasan != null && s.Pembahasan.ToLower().Contains(search)));
            }

            var list = filtered.ToList();

            if (list.Count == 0)
            {
                var emptyBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromRgb(30, 41, 59)),
                    CornerRadius = new CornerRadius(16),
                    Padding = new Thickness(32),
                    BorderBrush = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                    BorderThickness = new Thickness(1),
                    HorizontalAlignment = HorizontalAlignment.Center,
                    Margin = new Thickness(0, 40, 0, 0)
                };
                var emptyStack = new StackPanel { HorizontalAlignment = HorizontalAlignment.Center };
                emptyStack.Children.Add(new TextBlock
                {
                    Text = "🔍 Tidak Ada Soal yang Cocok",
                    FontSize = 18,
                    FontWeight = FontWeights.Bold,
                    Foreground = Brushes.White,
                    HorizontalAlignment = HorizontalAlignment.Center,
                    Margin = new Thickness(0, 0, 0, 6)
                });
                emptyStack.Children.Add(new TextBlock
                {
                    Text = "Coba ubah filter mata kuliah, topik, atau kata kunci pencarian.",
                    FontSize = 13,
                    Foreground = new SolidColorBrush(Color.FromRgb(148, 163, 184)),
                    HorizontalAlignment = HorizontalAlignment.Center
                });
                emptyBorder.Child = emptyStack;
                PanelCardsContainer.Children.Add(emptyBorder);
                return;
            }

            for (int i = 0; i < list.Count; i++)
            {
                PanelCardsContainer.Children.Add(BuildQuestionCard(list[i], i + 1));
            }
        }

        private UIElement BuildQuestionCard(Soal s, int index)
        {
            var card = new Border
            {
                Background = new SolidColorBrush(Color.FromRgb(30, 41, 59)),
                CornerRadius = new CornerRadius(16),
                Padding = new Thickness(24),
                BorderBrush = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                BorderThickness = new Thickness(1),
                Margin = new Thickness(0, 0, 0, 18)
            };

            var stack = new StackPanel();

            // 1. Header Badges
            var badgeWrap = new WrapPanel { Orientation = Orientation.Horizontal, Margin = new Thickness(0, 0, 0, 14) };

            // Number badge
            badgeWrap.Children.Add(CreateBadge($"#{index}", Color.FromRgb(15, 23, 42), new SolidColorBrush(Color.FromRgb(148, 163, 184))));

            // Matkul badge
            var mk = s.Materi?.MataKuliah;
            if (mk != null)
            {
                badgeWrap.Children.Add(CreateBadge($"{mk.Icon} {mk.Singkatan}", Color.FromRgb(124, 58, 237), Brushes.White));
            }

            // Materi badge
            if (s.Materi != null)
            {
                badgeWrap.Children.Add(CreateBadge($"P{s.Materi.Pertemuan}: {s.Materi.Judul}", Color.FromRgb(37, 99, 235), Brushes.White));
            }

            // Tipe Soal badge
            string tipeText = s.Tipe == TipeSoal.PilihanGanda ? "Pilihan Ganda" : "Essay";
            badgeWrap.Children.Add(CreateBadge(tipeText, s.Tipe == TipeSoal.PilihanGanda ? Color.FromRgb(16, 185, 129) : Color.FromRgb(245, 158, 11), Brushes.White));

            if (s.HasGambar)
            {
                badgeWrap.Children.Add(CreateBadge("🖼️ Ada Diagram", Color.FromRgb(6, 182, 212), Brushes.White));
            }

            if (s.HasPdf)
            {
                badgeWrap.Children.Add(CreateBadge("📄 Ada PDF", Color.FromRgb(239, 68, 68), Brushes.White));
            }

            stack.Children.Add(badgeWrap);

            // 2. Question Text
            stack.Children.Add(new TextBlock
            {
                Text = s.Pertanyaan,
                FontSize = 16,
                FontWeight = FontWeights.SemiBold,
                Foreground = Brushes.White,
                TextWrapping = TextWrapping.Wrap,
                LineHeight = 24,
                Margin = new Thickness(0, 0, 0, 14)
            });

            // 3. Diagram / Gambar Preview (if any)
            if (s.HasGambar && File.Exists(s.GambarPath))
            {
                var imgBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromRgb(15, 23, 42)),
                    CornerRadius = new CornerRadius(12),
                    BorderBrush = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                    BorderThickness = new Thickness(1),
                    Padding = new Thickness(12),
                    Margin = new Thickness(0, 0, 0, 14)
                };

                var imgStack = new StackPanel();

                try
                {
                    var bitmap = new BitmapImage();
                    bitmap.BeginInit();
                    bitmap.UriSource = new Uri(s.GambarPath);
                    bitmap.CacheOption = BitmapCacheOption.OnLoad;
                    bitmap.EndInit();

                    var img = new Image
                    {
                        Source = bitmap,
                        MaxHeight = 260,
                        Stretch = Stretch.Uniform,
                        HorizontalAlignment = HorizontalAlignment.Left
                    };
                    imgStack.Children.Add(img);
                }
                catch { }

                var btnZoom = new Button
                {
                    Content = "🔍 Buka Gambar Resolusi Penuh",
                    Height = 32,
                    HorizontalAlignment = HorizontalAlignment.Left,
                    Margin = new Thickness(0, 8, 0, 0),
                    Background = new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                    Foreground = Brushes.White,
                    BorderThickness = new Thickness(0)
                };
                btnZoom.Click += (o, e) =>
                {
                    try { Process.Start(new ProcessStartInfo(s.GambarPath!) { UseShellExecute = true }); }
                    catch (Exception ex) { MessageBox.Show($"Gagal membuka gambar: {ex.Message}"); }
                };
                imgStack.Children.Add(btnZoom);

                imgBorder.Child = imgStack;
                stack.Children.Add(imgBorder);
            }

            // 4. PDF Document Box (if any)
            if (s.HasPdf && File.Exists(s.PdfPath))
            {
                var pdfBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromArgb(40, 37, 99, 235)),
                    CornerRadius = new CornerRadius(10),
                    BorderBrush = new SolidColorBrush(Color.FromArgb(90, 37, 99, 235)),
                    BorderThickness = new Thickness(1),
                    Padding = new Thickness(14),
                    Margin = new Thickness(0, 0, 0, 14)
                };

                var pdfGrid = new Grid();
                pdfGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = GridLength.Auto });
                pdfGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
                pdfGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = GridLength.Auto });

                var iconTxt = new TextBlock
                {
                    Text = "📄",
                    FontSize = 24,
                    VerticalAlignment = VerticalAlignment.Center,
                    Margin = new Thickness(0, 0, 12, 0)
                };
                Grid.SetColumn(iconTxt, 0);
                pdfGrid.Children.Add(iconTxt);

                var metaStack = new StackPanel { VerticalAlignment = VerticalAlignment.Center };
                metaStack.Children.Add(new TextBlock
                {
                    Text = s.PdfName ?? Path.GetFileName(s.PdfPath),
                    FontWeight = FontWeights.Bold,
                    Foreground = Brushes.White,
                    FontSize = 13
                });
                metaStack.Children.Add(new TextBlock
                {
                    Text = "Dokumen Lampiran Studi Kasus Soal (PDF)",
                    FontSize = 11,
                    Foreground = new SolidColorBrush(Color.FromRgb(148, 163, 184))
                });
                Grid.SetColumn(metaStack, 1);
                pdfGrid.Children.Add(metaStack);

                var btnOpenPdf = new Button
                {
                    Content = "👁️ Buka PDF",
                    Height = 32,
                    Padding = new Thickness(12, 0, 12, 0),
                    Background = new SolidColorBrush(Color.FromRgb(37, 99, 235)),
                    Foreground = Brushes.White,
                    BorderThickness = new Thickness(0),
                    VerticalAlignment = VerticalAlignment.Center
                };
                btnOpenPdf.Click += (o, e) =>
                {
                    try { Process.Start(new ProcessStartInfo(s.PdfPath!) { UseShellExecute = true }); }
                    catch (Exception ex) { MessageBox.Show($"Gagal membuka PDF: {ex.Message}"); }
                };
                Grid.SetColumn(btnOpenPdf, 2);
                pdfGrid.Children.Add(btnOpenPdf);

                pdfBorder.Child = pdfGrid;
                stack.Children.Add(pdfBorder);
            }

            // 5. Choices (PG) or Evaluation (Essay)
            if (s.Tipe == TipeSoal.PilihanGanda)
            {
                var optionsGrid = new UniformGrid { Columns = 2, Margin = new Thickness(0, 0, 0, 10) };
                string[] choices = s.GetPilihan();
                string[] letters = { "A", "B", "C", "D" };

                for (int pi = 0; pi < 4; pi++)
                {
                    bool isCorrect = _showKunci && (pi == s.JawabanBenar);
                    var optBorder = new Border
                    {
                        Background = isCorrect
                            ? new SolidColorBrush(Color.FromArgb(50, 16, 185, 129))
                            : new SolidColorBrush(Color.FromRgb(15, 23, 42)),
                        BorderBrush = isCorrect
                            ? new SolidColorBrush(Color.FromRgb(16, 185, 129))
                            : new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                        BorderThickness = new Thickness(1.5),
                        CornerRadius = new CornerRadius(10),
                        Padding = new Thickness(12, 10, 12, 10),
                        Margin = new Thickness(4)
                    };

                    var optStack = new StackPanel { Orientation = Orientation.Horizontal };
                    var letterBadge = new Border
                    {
                        Width = 26,
                        Height = 26,
                        CornerRadius = new CornerRadius(6),
                        Background = isCorrect
                            ? new SolidColorBrush(Color.FromRgb(16, 185, 129))
                            : new SolidColorBrush(Color.FromRgb(51, 65, 85)),
                        Margin = new Thickness(0, 0, 10, 0),
                        Child = new TextBlock
                        {
                            Text = letters[pi],
                            FontWeight = FontWeights.Bold,
                            Foreground = Brushes.White,
                            HorizontalAlignment = HorizontalAlignment.Center,
                            VerticalAlignment = VerticalAlignment.Center
                        }
                    };
                    optStack.Children.Add(letterBadge);

                    var textOpt = new TextBlock
                    {
                        Text = choices[pi],
                        Foreground = isCorrect ? Brushes.White : new SolidColorBrush(Color.FromRgb(203, 213, 225)),
                        FontWeight = isCorrect ? FontWeights.SemiBold : FontWeights.Normal,
                        VerticalAlignment = VerticalAlignment.Center,
                        TextWrapping = TextWrapping.Wrap
                    };
                    optStack.Children.Add(textOpt);

                    if (isCorrect)
                    {
                        var tagCorrect = new TextBlock
                        {
                            Text = "✓ KUNCI",
                            FontWeight = FontWeights.Bold,
                            FontSize = 11,
                            Foreground = new SolidColorBrush(Color.FromRgb(52, 211, 153)),
                            Margin = new Thickness(8, 0, 0, 0),
                            VerticalAlignment = VerticalAlignment.Center
                        };
                        optStack.Children.Add(tagCorrect);
                    }

                    optBorder.Child = optStack;
                    optionsGrid.Children.Add(optBorder);
                }

                stack.Children.Add(optionsGrid);
            }
            else
            {
                // Essay
                var essayBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromArgb(30, 245, 158, 11)),
                    CornerRadius = new CornerRadius(10),
                    BorderBrush = new SolidColorBrush(Color.FromArgb(70, 245, 158, 11)),
                    BorderThickness = new Thickness(1),
                    Padding = new Thickness(14),
                    Margin = new Thickness(0, 0, 0, 10)
                };

                var essayStack = new StackPanel();
                if (_showKunci)
                {
                    essayStack.Children.Add(new TextBlock
                    {
                        Text = "Kata Kunci Penilaian Resmi:",
                        FontSize = 12,
                        FontWeight = FontWeights.Bold,
                        Foreground = new SolidColorBrush(Color.FromRgb(245, 158, 11)),
                        Margin = new Thickness(0, 0, 0, 6)
                    });
                    var kwWrap = new WrapPanel { Orientation = Orientation.Horizontal };
                    foreach (var kw in s.GetKataKunci())
                    {
                        kwWrap.Children.Add(CreateBadge($"✓ {kw.Trim()}", Color.FromRgb(15, 23, 42), new SolidColorBrush(Color.FromRgb(245, 158, 11))));
                    }
                    essayStack.Children.Add(kwWrap);
                }
                else
                {
                    essayStack.Children.Add(new TextBlock
                    {
                        Text = "Tipe Soal Essay — Tulis analisis dan jawaban mandiri untuk menguji pemahaman.",
                        FontSize = 12,
                        Foreground = new SolidColorBrush(Color.FromRgb(148, 163, 184)),
                        FontStyle = FontStyles.Italic
                    });
                }

                essayBorder.Child = essayStack;
                stack.Children.Add(essayBorder);
            }

            // 6. Explanation / Pembahasan Box (when Kunci is ON)
            if (_showKunci && !string.IsNullOrEmpty(s.Pembahasan))
            {
                var pembBorder = new Border
                {
                    Background = new SolidColorBrush(Color.FromArgb(40, 6, 182, 212)),
                    CornerRadius = new CornerRadius(10),
                    BorderBrush = new SolidColorBrush(Color.FromArgb(90, 6, 182, 212)),
                    BorderThickness = new Thickness(1),
                    Padding = new Thickness(14),
                    Margin = new Thickness(0, 6, 0, 0)
                };

                var pembStack = new StackPanel();
                pembStack.Children.Add(new TextBlock
                {
                    Text = "💡 PEMBAHASAN & SOLUSI JAWABAN",
                    FontSize = 11,
                    FontWeight = FontWeights.Bold,
                    Foreground = new SolidColorBrush(Color.FromRgb(103, 232, 249)),
                    Margin = new Thickness(0, 0, 0, 4)
                });
                pembStack.Children.Add(new TextBlock
                {
                    Text = s.Pembahasan,
                    FontSize = 13,
                    Foreground = new SolidColorBrush(Color.FromRgb(226, 232, 240)),
                    TextWrapping = TextWrapping.Wrap,
                    LineHeight = 20
                });

                pembBorder.Child = pembStack;
                stack.Children.Add(pembBorder);
            }

            card.Child = stack;
            return card;
        }

        private Border CreateBadge(string text, Color bg, Brush fg)
        {
            return new Border
            {
                Background = new SolidColorBrush(bg),
                CornerRadius = new CornerRadius(6),
                Padding = new Thickness(8, 3, 8, 3),
                Margin = new Thickness(0, 0, 8, 4),
                Child = new TextBlock
                {
                    Text = text,
                    FontSize = 11,
                    FontWeight = FontWeights.SemiBold,
                    Foreground = fg
                }
            };
        }
    }
}
