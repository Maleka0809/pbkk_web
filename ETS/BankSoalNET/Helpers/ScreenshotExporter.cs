using System;
using System.IO;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Threading;
using BankSoalNET.Models;
using BankSoalNET.Services;
using BankSoalNET.Views;

namespace BankSoalNET.Helpers
{
    public static class ScreenshotExporter
    {
        public static async Task RunAsync()
        {
            string outputDir = @"c:\Users\Maleka Ghaniya\Downloads\PBKK-web\ETS_WEB\dokumentasi";
            Directory.CreateDirectory(outputDir);
            string logFile = Path.Combine(outputDir, "capture_log.txt");
            File.WriteAllText(logFile, "Starting capture at " + DateTime.Now + "\n");

            Application.Current.ShutdownMode = ShutdownMode.OnExplicitShutdown;

            var db = new DatabaseService();
            var adminUser = await db.LoginAsync("admin", "admin123") ?? new User
            {
                Id = 1,
                Username = "admin",
                NamaLengkap = "Administrator Dosen",
                Role = "admin"
            };

            var mhsUser = await db.LoginAsync("mhs01", "mhs123") ?? new User
            {
                Id = 2,
                Username = "mhs01",
                NamaLengkap = "Ahmad Rizki",
                Role = "mahasiswa"
            };

            // 1. Capture Login Window
            try
            {
                var loginWindow = new LoginWindow();
                loginWindow.Width = 480;
                loginWindow.Height = 720;
                loginWindow.WindowStartupLocation = WindowStartupLocation.CenterScreen;
                loginWindow.Show();
                await WaitForRender(600);
                SaveWindowBitmap(loginWindow, Path.Combine(outputDir, "01_login_window.png"));
                File.AppendAllText(logFile, "01 Login captured\n");

                // 2. Capture Register Window
                loginWindow.TabRegister.IsChecked = true;
                await WaitForRender(400);
                SaveWindowBitmap(loginWindow, Path.Combine(outputDir, "02_register_window.png"));
                File.AppendAllText(logFile, "02 Register captured\n");
                loginWindow.Close();
            }
            catch (Exception ex)
            {
                File.AppendAllText(logFile, "Error login: " + ex + "\n");
            }

            // 3. Admin MainShell Views
            try
            {
                var adminShell = new MainShell(adminUser, db);
                adminShell.Width = 1280;
                adminShell.Height = 800;
                adminShell.WindowStartupLocation = WindowStartupLocation.CenterScreen;
                adminShell.Show();
                await WaitForRender(600);

                // 03: Admin Dashboard
                try
                {
                    adminShell.ShowAdminDashboard();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "03_admin_dashboard.png"));
                    File.AppendAllText(logFile, "03 Admin Dashboard captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 03: " + ex + "\n"); }

                // 04: Kelola Matkul
                try
                {
                    adminShell.ShowKelolaMatkul();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "04_kelola_matkul.png"));
                    File.AppendAllText(logFile, "04 Kelola Matkul captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 04: " + ex + "\n"); }

                // 05: Kelola Materi
                try
                {
                    adminShell.ShowKelolaMateri();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "05_kelola_materi.png"));
                    File.AppendAllText(logFile, "05 Kelola Materi captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 05: " + ex + "\n"); }

                // 06: Kelola Soal
                try
                {
                    adminShell.ShowKelolaSoal();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "06_kelola_soal.png"));
                    File.AppendAllText(logFile, "06 Kelola Soal captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 06: " + ex + "\n"); }

                // 07: Bank Soal & Pembahasan
                try
                {
                    adminShell.ShowBankSoal();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "07_bank_soal_katalog.png"));
                    File.AppendAllText(logFile, "07 Bank Soal captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 07: " + ex + "\n"); }

                // 08: Dokumen PDF Reader
                try
                {
                    adminShell.ShowDokumenPdf();
                    await WaitForRender(1000);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "08_dokumen_pdf.png"));
                    File.AppendAllText(logFile, "08 Dokumen PDF captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 08: " + ex + "\n"); }

                // 09: Rekap Hasil Mahasiswa (Admin)
                try
                {
                    adminShell.ShowHasilMahasiswa();
                    await WaitForRender(600);
                    SaveWindowBitmap(adminShell, Path.Combine(outputDir, "09_rekap_nilai_admin.png"));
                    File.AppendAllText(logFile, "09 Rekap Hasil captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 09: " + ex + "\n"); }

                adminShell.Close();
            }
            catch (Exception ex)
            {
                File.AppendAllText(logFile, "Error admin shell: " + ex + "\n");
            }

            // 4. Mahasiswa MainShell Views
            try
            {
                var mhsShell = new MainShell(mhsUser, db);
                mhsShell.Width = 1280;
                mhsShell.Height = 800;
                mhsShell.WindowStartupLocation = WindowStartupLocation.CenterScreen;
                mhsShell.Show();
                await WaitForRender(600);

                // 10: Mode Belajar Mahasiswa
                try
                {
                    mhsShell.ShowModeBelajar();
                    await WaitForRender(600);
                    SaveWindowBitmap(mhsShell, Path.Combine(outputDir, "10_mode_belajar_mahasiswa.png"));
                    File.AppendAllText(logFile, "10 Mode Belajar captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 10: " + ex + "\n"); }

                // 11: Mode Scoring / Ujian Interaktif
                try
                {
                    mhsShell.ShowModeScoring();
                    await WaitForRender(600);
                    SaveWindowBitmap(mhsShell, Path.Combine(outputDir, "11_mode_scoring_ujian.png"));
                    File.AppendAllText(logFile, "11 Mode Scoring captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 11: " + ex + "\n"); }

                // 12: Riwayat Nilai Mahasiswa
                try
                {
                    mhsShell.ShowRiwayatNilai();
                    await WaitForRender(600);
                    SaveWindowBitmap(mhsShell, Path.Combine(outputDir, "12_riwayat_nilai_mahasiswa.png"));
                    File.AppendAllText(logFile, "12 Riwayat Nilai captured\n");
                }
                catch (Exception ex) { File.AppendAllText(logFile, "Error 12: " + ex + "\n"); }

                mhsShell.Close();
            }
            catch (Exception ex)
            {
                File.AppendAllText(logFile, "Error mhs shell: " + ex + "\n");
            }

            File.AppendAllText(logFile, "ALL CAPTURE FINISHED AT " + DateTime.Now + "\n");
            Application.Current.Shutdown();
        }

        private static async Task WaitForRender(int milliseconds)
        {
            await Task.Delay(milliseconds);
            DoEvents();
        }

        private static void DoEvents()
        {
            var frame = new DispatcherFrame();
            Dispatcher.CurrentDispatcher.BeginInvoke(DispatcherPriority.Background,
                new DispatcherOperationCallback(ExitFrame), frame);
            Dispatcher.PushFrame(frame);
        }

        private static object? ExitFrame(object state)
        {
            var frame = state as DispatcherFrame;
            if (frame != null) frame.Continue = false;
            return null;
        }

        private static void SaveWindowBitmap(Window window, string filePath)
        {
            window.UpdateLayout();
            int width = (int)Math.Max(window.ActualWidth, window.Width);
            int height = (int)Math.Max(window.ActualHeight, window.Height);
            if (width <= 0) width = 1280;
            if (height <= 0) height = 800;

            var rtb = new RenderTargetBitmap(width, height, 96, 96, PixelFormats.Pbgra32);
            rtb.Render(window);

            var encoder = new PngBitmapEncoder();
            encoder.Frames.Add(BitmapFrame.Create(rtb));

            using var fs = new FileStream(filePath, FileMode.Create, FileAccess.Write);
            encoder.Save(fs);
        }
    }
}
