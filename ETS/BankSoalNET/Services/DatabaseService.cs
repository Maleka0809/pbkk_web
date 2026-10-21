using BankSoalNET.Data;
using BankSoalNET.Models;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Threading.Tasks;

namespace BankSoalNET.Services
{
    public class DatabaseService
    {
        private readonly AppDbContext _db;
        public static string AttachmentsFolder => Path.Combine(
            Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData),
            "BankSoalNET", "Attachments");

        public DatabaseService()
        {
            _db = new AppDbContext();
            _db.Database.EnsureCreated();
            EnsureSchemaUpdated();
            SeedData();
        }

        private void EnsureSchemaUpdated()
        {
            try
            {
                Directory.CreateDirectory(AttachmentsFolder);

                _db.Database.ExecuteSqlRaw(@"
                    CREATE TABLE IF NOT EXISTS ""MataKuliahs"" (
                        ""Id"" INTEGER NOT NULL CONSTRAINT ""PK_MataKuliahs"" PRIMARY KEY AUTOINCREMENT,
                        ""Kode"" TEXT NOT NULL,
                        ""Nama"" TEXT NOT NULL,
                        ""Singkatan"" TEXT NOT NULL,
                        ""Sks"" INTEGER NOT NULL,
                        ""Semester"" INTEGER NOT NULL,
                        ""Icon"" TEXT NOT NULL,
                        ""Warna"" TEXT NOT NULL,
                        ""Deskripsi"" TEXT NOT NULL,
                        ""CreatedAt"" TEXT NOT NULL
                    );
                ");

                TryAddColumn("Materis", "MataKuliahId", "INTEGER NOT NULL DEFAULT 1");
                TryAddColumn("Soals", "GambarPath", "TEXT NULL");
                TryAddColumn("Soals", "GambarDesc", "TEXT NULL");
                TryAddColumn("Soals", "PdfPath", "TEXT NULL");
                TryAddColumn("Soals", "PdfName", "TEXT NULL");
                TryAddColumn("Soals", "Pembahasan", "TEXT NULL");

                _db.Database.ExecuteSqlRaw(@"
                    CREATE TABLE IF NOT EXISTS ""DokumenSoals"" (
                        ""Id"" INTEGER NOT NULL CONSTRAINT ""PK_DokumenSoals"" PRIMARY KEY AUTOINCREMENT,
                        ""MataKuliahId"" INTEGER NOT NULL,
                        ""Judul"" TEXT NOT NULL,
                        ""Deskripsi"" TEXT NOT NULL,
                        ""PdfPath"" TEXT NOT NULL,
                        ""PdfName"" TEXT NOT NULL,
                        ""UkuranFile"" TEXT NOT NULL,
                        ""TanggalUpload"" TEXT NOT NULL
                    );
                ");
            }
            catch { /* schema already up to date */ }
        }

        private void TryAddColumn(string table, string column, string typeDefinition)
        {
            try
            {
#pragma warning disable EF1002
                _db.Database.ExecuteSqlRaw($@"ALTER TABLE ""{table}"" ADD COLUMN ""{column}"" {typeDefinition};");
#pragma warning restore EF1002
            }
            catch { }
        }

        // ==================== SEED ====================
        private void SeedData()
        {
            // Seed Users if empty
            if (!_db.Users.Any())
            {
                _db.Users.AddRange(
                    new User { Username = "admin", Password = "admin123", NamaLengkap = "Administrator", Role = "admin" },
                    new User { Username = "mhs01", Password = "mhs123", NamaLengkap = "Mahasiswa 01", Role = "mahasiswa" },
                    new User { Username = "mhs02", Password = "mhs123", NamaLengkap = "Mahasiswa 02", Role = "mahasiswa" }
                );
                _db.SaveChanges();
            }

            // Seed Mata Kuliah if empty
            if (!_db.MataKuliahs.Any())
            {
                var mkList = new List<MataKuliah>
                {
                    new MataKuliah
                    {
                        Kode = "IF401",
                        Nama = "Pemrograman Berbasis Kerangka Kerja",
                        Singkatan = "PBKK (.NET)",
                        Sks = 3,
                        Semester = 4,
                        Icon = "⚡",
                        Warna = "purple",
                        Deskripsi = "Pengembangan aplikasi desktop dan enterprise menggunakan .NET Framework, C#, XAML, WPF, MVVM, dan UWP."
                    },
                    new MataKuliah
                    {
                        Kode = "IF302",
                        Nama = "Pemrograman Web Modern",
                        Singkatan = "Pemrograman Web",
                        Sks = 3,
                        Semester = 3,
                        Icon = "🌐",
                        Warna = "cyan",
                        Deskripsi = "Arsitektur web client-server, HTML5 Semantic, CSS Grid/Flexbox, JavaScript ES6+, DOM manipulation, dan Async REST APIs."
                    },
                    new MataKuliah
                    {
                        Kode = "IF201",
                        Nama = "Sistem Basis Data & SQL",
                        Singkatan = "Basis Data",
                        Sks = 4,
                        Semester = 2,
                        Icon = "🗄️",
                        Warna = "orange",
                        Deskripsi = "Konsep relational database management system (RDBMS), ERD, Normalisasi 1NF-3NF, DDL/DML, indexing, dan transaksi ACID."
                    }
                };

                _db.MataKuliahs.AddRange(mkList);
                _db.SaveChanges();
            }

            // Create sample files
            string samplePdfPath = Path.Combine(AttachmentsFolder, "Studi_Kasus_WPF_MVVM.pdf");
            if (!File.Exists(samplePdfPath))
            {
                try
                {
                    string pdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>/Contents 4 0 R>>endobj\n4 0 obj<</Length 92>>stream\nBT /F1 18 Tf 50 720 Td (Bank Soal .NET - Dokumen Studi Kasus Arsitektur WPF & MVVM) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000216 00000 n \ntrailer<</Size 5/Root 1 0 R>>\nstartxref\n360\n%%EOF";
                    File.WriteAllText(samplePdfPath, pdfContent);
                }
                catch { }
            }

            // Seed Materi if empty
            if (!_db.Materis.Any())
            {
                var mk1 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF401") ?? _db.MataKuliahs.First();
                var mk2 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF302") ?? mk1;
                var mk3 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF201") ?? mk1;

                var materis = new List<Materi>
                {
                    // PBKK (.NET)
                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 1, Judul = "Pengenalan Framework", Deskripsi = "Konsep dasar framework dalam pengembangan perangkat lunak modern.", Tags = "framework,.net,clr,bcl",
                        Konten = "Apa itu Framework?\nFramework adalah kerangka kerja yang menyediakan struktur dasar untuk membangun aplikasi.\n\nKomponen Utama .NET:\n• CLR (Common Language Runtime)\n• BCL (Base Class Library)\n• C#, VB.NET, F#" },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 2, Judul = "NET Framework & Latihan Aplikasi Desktop 1", Deskripsi = "Pengenalan mendalam tentang .NET Framework dan praktik membuat aplikasi desktop pertama.", Tags = "winforms,desktop,clr,fcl",
                        Konten = "Arsitektur .NET Framework\n.NET Framework terdiri dari CLR dan FCL (Framework Class Library)." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 3, Judul = "Aplikasi Media Capture", Deskripsi = "Membangun aplikasi untuk menangkap media menggunakan .NET dan Windows APIs.", Tags = "media,capture,webcam,directshow,aforge",
                        Konten = "Media Capture di .NET melibatkan pengambilan data dari kamera dan mikrofon menggunakan DirectShow atau Windows MediaCapture." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 4, Judul = "Rich UI dengan WPF & Latihan WPF", Deskripsi = "Membangun antarmuka kaya menggunakan WPF dengan XAML dan data binding.", Tags = "wpf,xaml,mvvm,databinding,animation",
                        Konten = "Windows Presentation Foundation (WPF) menggunakan XAML deklaratif dan rendering DirectX hardware acceleration." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 5, Judul = "UWP - Universal Windows Platform", Deskripsi = "Universal Windows Platform untuk membangun aplikasi yang berjalan di semua perangkat Windows.", Tags = "uwp,universal,windows,winrt,adaptive",
                        Konten = "UWP berjalan di desktop, tablet, dan Xbox menggunakan WinRT APIs." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 6, Judul = "Membuat Aplikasi dengan NET", Deskripsi = "Praktik komprehensif membangun aplikasi lengkap dengan .NET modern.", Tags = "dotnet,di,ef-core,deployment,solid",
                        Konten = "Membangun aplikasi .NET modern dengan Entity Framework Core dan Dependency Injection." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 7, Judul = "Windows Presentation Foundation", Deskripsi = "WPF lanjutan: custom controls, triggers, behaviors, dan integrasi database.", Tags = "wpf,custom-control,dependency-property,triggers,commands",
                        Konten = "WPF Lanjutan: Dependency Properties, Commands, Triggers, dan Resource Dictionaries." },

                    new Materi { MataKuliahId = mk1.Id, Pertemuan = 8, Judul = "Latihan WPF - Membuat Dashboard", Deskripsi = "Praktik membuat dashboard modern dengan WPF, chart, data binding, dan navigasi.", Tags = "wpf,dashboard,livecharts,mvvm,material-design",
                        Konten = "Latihan: Membangun Dashboard WPF Profesional dengan Material Design." },

                    // Pemrograman Web
                    new Materi { MataKuliahId = mk2.Id, Pertemuan = 1, Judul = "Arsitektur Web & HTTP", Deskripsi = "Client-Server, Request-Response cycle, HTTP methods, headers, dan status code.", Tags = "web,http,client-server,rest",
                        Konten = "Arsitektur Web Modern: Protokol HTTP/HTTPS, metode GET, POST, PUT, DELETE, dan arsitektur RESTful API." },

                    // Basis Data
                    new Materi { MataKuliahId = mk3.Id, Pertemuan = 1, Judul = "Perancangan Basis Data & ERD", Deskripsi = "Entity-Relationship Diagram, kardinalitas relasi, normalisasi 1NF-3NF.", Tags = "database,erd,sql,normalisasi",
                        Konten = "Perancangan Basis Data: Entitas, Atribut, Primary Key, Foreign Key, dan Relasi One-to-Many serta Many-to-Many." }
                };

                _db.Materis.AddRange(materis);
                _db.SaveChanges();

                // Seed Soals
                var m1 = materis[0]; var m2 = materis[1]; var m4 = materis[3];
                var mWeb = materis[8]; var mDb = materis[9];

                _db.Soals.AddRange(
                    new Soal
                    {
                        Materi = m1,
                        Tipe = TipeSoal.PilihanGanda,
                        Pertanyaan = "Apa kepanjangan dari CLR dalam ekosistem .NET?",
                        PilihanA = "Common Language Runtime",
                        PilihanB = "Core Library Runtime",
                        PilihanC = "Central Logic Runtime",
                        PilihanD = "Common Library Reference",
                        JawabanBenar = 0,
                        Pembahasan = "CLR (Common Language Runtime) adalah mesin eksekusi utama .NET yang menangani memori, thread, exception, dan keamanan kode IL."
                    },
                    new Soal
                    {
                        Materi = m1,
                        Tipe = TipeSoal.PilihanGanda,
                        Pertanyaan = "Prinsip mana yang berarti \"jangan ulangi kode yang sama berulang kali\"?",
                        PilihanA = "OOP",
                        PilihanB = "DRY (Don't Repeat Yourself)",
                        PilihanC = "SOLID",
                        PilihanD = "MVC",
                        JawabanBenar = 1,
                        Pembahasan = "DRY (Don't Repeat Yourself) bertujuan mengurangi redundansi kode dengan memanfaatkan abstraksi fungsi atau reusable library."
                    },
                    new Soal
                    {
                        Materi = m1,
                        Tipe = TipeSoal.Essay,
                        Pertanyaan = "Jelaskan apa yang dimaksud dengan Framework dalam pengembangan perangkat lunak dan sebutkan keuntungannya!",
                        KataKunci = "kerangka kerja,struktur,library,produktivitas,reusable,pola desain",
                        Pembahasan = "Framework adalah kerangka kerja perangkat lunak yang menyediakan template arsitektural dan library siap pakai untuk mempercepat proses pembuatan aplikasi."
                    },
                    new Soal
                    {
                        Materi = m4,
                        Tipe = TipeSoal.PilihanGanda,
                        Pertanyaan = "Pola arsitektur manakah yang memisahkan View dari Logic menggunakan Data Binding dua arah di WPF?",
                        PilihanA = "MVC (Model-View-Controller)",
                        PilihanB = "MVP (Model-View-Presenter)",
                        PilihanC = "MVVM (Model-View-ViewModel)",
                        PilihanD = "Active Record",
                        JawabanBenar = 2,
                        PdfPath = samplePdfPath,
                        PdfName = "Studi_Kasus_WPF_MVVM.pdf",
                        Pembahasan = "MVVM (Model-View-ViewModel) memisahkan UI (XAML View) dari logika (ViewModel) tanpa coupling ketat, menggunakan Data Binding dan INotifyPropertyChanged."
                    },
                    new Soal
                    {
                        Materi = mWeb,
                        Tipe = TipeSoal.PilihanGanda,
                        Pertanyaan = "HTTP status code 201 Created menunjukkan bahwa...",
                        PilihanA = "Permintaan berhasil dan entitas baru telah dibuat di server",
                        PilihanB = "Halaman tidak ditemukan di server",
                        PilihanC = "Server mengalami kegagalan internal",
                        PilihanD = "Klien tidak memiliki hak akses (Unauthorized)",
                        JawabanBenar = 0,
                        Pembahasan = "Status code 201 Created dikembalikan ketika request POST berhasil menciptakan record sumber daya baru di database server."
                    },
                    new Soal
                    {
                        Materi = mDb,
                        Tipe = TipeSoal.PilihanGanda,
                        Pertanyaan = "Sebuah tabel relasional dikatakan memenuhi bentuk normal ketiga (3NF) jika...",
                        PilihanA = "Tabel memiliki banyak kolom bernilai array",
                        PilihanB = "Tabel sudah memenuhi 2NF dan tidak memiliki ketergantungan transitif",
                        PilihanC = "Tabel tidak memiliki primary key",
                        PilihanD = "Semua kolom bernilai teks semata",
                        JawabanBenar = 1,
                        Pembahasan = "3NF mensyaratkan tabel sudah dalam 2NF dan setiap atribut non-primer tidak bergantung secara transitif pada primary key (hanya bergantung langsung pada primary key)."
                    }
                );

                _db.SaveChanges();
            }

            // Ensure existing Materis have valid MataKuliahId
            var defaultMk = _db.MataKuliahs.FirstOrDefault()?.Id ?? 1;
            var zeroMateri = _db.Materis.Where(m => m.MataKuliahId == 0).ToList();
            if (zeroMateri.Any())
            {
                foreach (var m in zeroMateri) m.MataKuliahId = defaultMk;
                _db.SaveChanges();
            }

            // Create sample PNG diagram if not exists
            string sampleImgPath = Path.Combine(AttachmentsFolder, "diagram_arsitektur_mvvm.png");
            if (!File.Exists(sampleImgPath))
            {
                try
                {
                    var visual = new System.Windows.Media.DrawingVisual();
                    using (var dc = visual.RenderOpen())
                    {
                        var bgBrush = new System.Windows.Media.SolidColorBrush(System.Windows.Media.Color.FromRgb(15, 23, 42));
                        dc.DrawRectangle(bgBrush, null, new System.Windows.Rect(0, 0, 640, 240));

                        var boxBrush = new System.Windows.Media.SolidColorBrush(System.Windows.Media.Color.FromRgb(30, 41, 59));
                        var vPen = new System.Windows.Media.Pen(new System.Windows.Media.SolidColorBrush(System.Windows.Media.Color.FromRgb(124, 58, 237)), 2);
                        var vmPen = new System.Windows.Media.Pen(new System.Windows.Media.SolidColorBrush(System.Windows.Media.Color.FromRgb(6, 182, 212)), 2);
                        var mPen = new System.Windows.Media.Pen(new System.Windows.Media.SolidColorBrush(System.Windows.Media.Color.FromRgb(16, 185, 129)), 2);

                        dc.DrawRoundedRectangle(boxBrush, vPen, new System.Windows.Rect(30, 60, 160, 110), 12, 12);
                        dc.DrawRoundedRectangle(boxBrush, vmPen, new System.Windows.Rect(240, 60, 160, 110), 12, 12);
                        dc.DrawRoundedRectangle(boxBrush, mPen, new System.Windows.Rect(450, 60, 160, 110), 12, 12);
                    }
                    var rtb = new System.Windows.Media.Imaging.RenderTargetBitmap(640, 240, 96, 96, System.Windows.Media.PixelFormats.Pbgra32);
                    rtb.Render(visual);
                    var encoder = new System.Windows.Media.Imaging.PngBitmapEncoder();
                    encoder.Frames.Add(System.Windows.Media.Imaging.BitmapFrame.Create(rtb));
                    using var stream = File.Create(sampleImgPath);
                    encoder.Save(stream);
                }
                catch { }
            }

            // Ensure at least one question has image and one has PDF
            var soalWithImg = _db.Soals.FirstOrDefault(s => s.GambarPath != null);
            if (soalWithImg == null && File.Exists(sampleImgPath))
            {
                var targetSoal = _db.Soals.FirstOrDefault(s => s.Pertanyaan.Contains("MVVM") || s.Pertanyaan.Contains("Pola desain"));
                if (targetSoal != null)
                {
                    targetSoal.GambarPath = sampleImgPath;
                    targetSoal.GambarDesc = "Diagram Pola Arsitektur MVVM (WPF)";
                    targetSoal.Pembahasan = "MVVM (Model-View-ViewModel) memisahkan tampilan deklaratif (View) dari logika bisnis (ViewModel) menggunakan data binding dua arah.";
                    _db.SaveChanges();
                }
            }

            var soalWithPdf = _db.Soals.FirstOrDefault(s => s.PdfPath != null);
            if (soalWithPdf == null && File.Exists(samplePdfPath))
            {
                var targetSoal = _db.Soals.FirstOrDefault(s => s.Pertanyaan.Contains("Rich UI") || s.Pertanyaan.Contains("XAML"));
                if (targetSoal != null)
                {
                    targetSoal.PdfPath = samplePdfPath;
                    targetSoal.PdfName = "Studi_Kasus_WPF_MVVM.pdf";
                    targetSoal.Pembahasan = "XAML adalah bahasa deklaratif berbasis XML yang digunakan di WPF untuk memisahkan desain UI dari kode C#.";
                    _db.SaveChanges();
                }
            }
            // Seed DokumenSoal (Full PDF Bank Soal) if empty
            if (!_db.DokumenSoals.Any())
            {
                var mk1 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF401") ?? _db.MataKuliahs.First();
                var mk2 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF302") ?? mk1;
                var mk3 = _db.MataKuliahs.FirstOrDefault(m => m.Kode == "IF201") ?? mk1;

                string pdfPbkk = Path.Combine(AttachmentsFolder, "Bank_Soal_Lengkap_ETS_PBKK_2024.pdf");
                string pdfWeb = Path.Combine(AttachmentsFolder, "Modul_Kisi_Kisi_Soal_Pemrograman_Web.pdf");
                string pdfDb = Path.Combine(AttachmentsFolder, "Kumpulan_Soal_Studi_Kasus_Basis_Data.pdf");

                GenerateSamplePdfFile(pdfPbkk, "BANK SOAL & PEMBAHASAN ETS PBKK (.NET)", "IF401 - PBKK (.NET)", new[]
                {
                    "1. Jelaskan perbedaan arsitektur .NET Framework tradisional dengan .NET Core / .NET 8 modern!",
                    "2. Apa keuntungan pola MVVM dibandingkan Code-Behind biasa pada aplikasi WPF enterprise?",
                    "3. Bagaimana mekanisme kerja Two-Way Data Binding dan INotifyPropertyChanged di WPF?",
                    "4. Rancanglah struktur kelas ViewModel untuk form login yang memiliki validasi input!",
                    "5. Sebutkan kelebihan penggunaan SQLite dibandingkan database client-server untuk desktop app!",
                    "KUNCI JAWABAN & PEMBAHASAN:",
                    "- MVVM mengeliminasi tight-coupling antara XAML UI dan logika bisnis aplikasi.",
                    "- Two-Way Binding menghubungkan properti UI secara otomatis ke properti ViewModel.",
                    "- SQLite bersifat serverless (in-process engine), mudah di-deploy tanpa instalasi server."
                });

                GenerateSamplePdfFile(pdfWeb, "KISI-KISI SOAL UJIAN PEMROGRAMAN WEB MODERN", "IF302 - Pemrograman Web", new[]
                {
                    "1. Jelaskan siklus Request-Response dalam arsitektur HTTP client-server!",
                    "2. Bandingkan metode HTTP GET vs POST dan jelaskan aspek keamanannya masing-masing!",
                    "3. Mengapa CSS Grid lebih disarankan untuk tata letak dua dimensi dibandingkan Flexbox?",
                    "4. Bagaimana mekanisme Asynchronous JavaScript (Promises & async/await) mencegah blocking UI?",
                    "5. Jelaskan implementasi RESTful API best practices untuk endpoint resource perpustakaan!",
                    "KUNCI JAWABAN & PEMBAHASAN:",
                    "- HTTP GET mengirim data lewat query string URL, sedangkan POST lewat request body.",
                    "- CSS Grid mengontrol baris dan kolom secara bersamaan (2D layout framework).",
                    "- Async/await bekerja di atas event loop untuk menunggu I/O tanpa membekukan thread utama."
                });

                GenerateSamplePdfFile(pdfDb, "STUDI KASUS & LATIHAN SOAL PERANCANGAN BASIS DATA", "IF201 - Basis Data", new[]
                {
                    "1. Jelaskan konsep Entity Relationship Diagram (ERD) beserta derajat kardinalitasnya!",
                    "2. Diberikan skema relasi unnormalized, lakukan tahapan normalisasi hingga mencapai 3NF!",
                    "3. Mengapa ketergantungan transitif harus dihilangkan pada proses normalisasi tahap ketiga?",
                    "4. Tuliskan query SQL DDL untuk membuat tabel Mahasiswa dan MataKuliah dengan FK cascade!",
                    "5. Jelaskan perbedaan mendasar antara INNER JOIN, LEFT JOIN, dan FULL OUTER JOIN!",
                    "KUNCI JAWABAN & PEMBAHASAN:",
                    "- 3NF menghilangkan anomali update, insert, dan delete dengan memisahkan transitif.",
                    "- Foreign Key dengan ON DELETE CASCADE menjaga integritas referensial data secara otomatis."
                });

                _db.DokumenSoals.AddRange(
                    new DokumenSoal
                    {
                        MataKuliahId = mk1.Id,
                        Judul = "Bank Soal & Pembahasan Lengkap ETS PBKK .NET",
                        Deskripsi = "Kumpulan soal ujian lengkap dengan pembahasan arsitektur .NET, WPF, XAML, dan MVVM.",
                        PdfPath = pdfPbkk,
                        PdfName = "Bank_Soal_Lengkap_ETS_PBKK_2024.pdf",
                        UkuranFile = "45 KB",
                        TanggalUpload = DateTime.Now
                    },
                    new DokumenSoal
                    {
                        MataKuliahId = mk2.Id,
                        Judul = "Modul Kisi-Kisi Soal Ujian Pemrograman Web Modern",
                        Deskripsi = "Ringkasan soal latihan HTTP, RESTful APIs, responsive CSS, dan DOM JavaScript.",
                        PdfPath = pdfWeb,
                        PdfName = "Modul_Kisi_Kisi_Soal_Pemrograman_Web.pdf",
                        UkuranFile = "42 KB",
                        TanggalUpload = DateTime.Now.AddHours(-2)
                    },
                    new DokumenSoal
                    {
                        MataKuliahId = mk3.Id,
                        Judul = "Studi Kasus & Kumpulan Soal Perancangan Basis Data",
                        Deskripsi = "Latihan soal ERD, teknik normalisasi 1NF-3NF, dan query SQL relasional.",
                        PdfPath = pdfDb,
                        PdfName = "Kumpulan_Soal_Studi_Kasus_Basis_Data.pdf",
                        UkuranFile = "44 KB",
                        TanggalUpload = DateTime.Now.AddDays(-1)
                    }
                );
                _db.SaveChanges();
            }
        }

        private static void GenerateSamplePdfFile(string path, string title, string course, string[] lines)
        {
            try
            {
                using var sw = new StreamWriter(path, false, System.Text.Encoding.ASCII);
                sw.WriteLine("%PDF-1.4");
                sw.WriteLine("1 0 obj <</Type/Catalog/Pages 2 0 R>> endobj");
                sw.WriteLine("2 0 obj <</Type/Pages/Kids[3 0 R]/Count 1>> endobj");
                sw.WriteLine("3 0 obj <</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<</F1 5 0 R>>>>/Contents 4 0 R>> endobj");

                var sbStream = new System.Text.StringBuilder();
                sbStream.AppendLine("BT");
                sbStream.AppendLine("/F1 16 Tf");
                sbStream.AppendLine("40 730 Td (" + EscapePdfText(title) + ") Tj");
                sbStream.AppendLine("/F1 11 Tf");
                sbStream.AppendLine("0 -24 Td (Mata Kuliah: " + EscapePdfText(course) + " | Dokumen Bank Soal Resmi) Tj");
                sbStream.AppendLine("0 -16 Td (----------------------------------------------------------------------------------------------------------------------------------) Tj");
                sbStream.AppendLine("/F1 10 Tf");

                int yStep = -22;
                foreach (var line in lines)
                {
                    sbStream.AppendLine($"0 {yStep} Td (" + EscapePdfText(line) + ") Tj");
                    yStep = -18;
                }

                sbStream.AppendLine("ET");
                string streamData = sbStream.ToString();

                sw.WriteLine($"4 0 obj <</Length {streamData.Length}>> stream");
                sw.Write(streamData);
                sw.WriteLine("endstream endobj");
                sw.WriteLine("5 0 obj <</Type/Font/Subtype/Type1/BaseFont/Helvetica>> endobj");
                sw.WriteLine("xref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000236 00000 n \n0000000450 00000 n \ntrailer <</Size 6/Root 1 0 R>>\nstartxref\n540\n%%EOF");
            }
            catch { }
        }

        private static string EscapePdfText(string text)
        {
            return text.Replace("\\", "\\\\").Replace("(", "\\(").Replace(")", "\\)");
        }

        // ==================== USERS ====================
        // Unified login: auto-detect role from database
        public async Task<User?> LoginAsync(string username, string password)
        {
            username = username.Trim().ToLower();
            return await _db.Users.FirstOrDefaultAsync(u =>
                u.Username.ToLower() == username && u.Password == password);
        }

        public async Task<User?> LoginAsync(string username, string password, string role)
        {
            return await _db.Users.FirstOrDefaultAsync(u =>
                u.Username == username && u.Password == password && u.Role == role);
        }

        public async Task<(bool Success, string Message, User? User)> RegisterAsync(string username, string password, string namaLengkap, string role)
        {
            username = username.Trim().ToLower();
            namaLengkap = namaLengkap.Trim();

            if (string.IsNullOrWhiteSpace(username) || string.IsNullOrWhiteSpace(password) || string.IsNullOrWhiteSpace(namaLengkap))
            {
                return (false, "Semua data pendaftaran wajib diisi!", null);
            }

            if (username.Length < 3)
            {
                return (false, "Username minimal 3 karakter!", null);
            }

            if (password.Length < 4)
            {
                return (false, "Password minimal 4 karakter!", null);
            }

            bool exists = await _db.Users.AnyAsync(u => u.Username.ToLower() == username);
            if (exists)
            {
                return (false, "Username sudah digunakan! Silakan pilih username lain.", null);
            }

            var user = new User
            {
                Username = username,
                Password = password,
                NamaLengkap = namaLengkap,
                Role = role.ToLower()
            };

            _db.Users.Add(user);
            await _db.SaveChangesAsync();

            return (true, "Akun berhasil didaftarkan!", user);
        }

        // ==================== MATA KULIAH ====================
        public async Task<List<MataKuliah>> GetAllMataKuliahAsync() =>
            await _db.MataKuliahs.OrderBy(m => m.Kode).ToListAsync();

        public async Task<MataKuliah?> GetMataKuliahByIdAsync(int id) =>
            await _db.MataKuliahs.Include(m => m.Materis).FirstOrDefaultAsync(m => m.Id == id);

        public async Task AddMataKuliahAsync(MataKuliah mk)
        {
            _db.MataKuliahs.Add(mk);
            await _db.SaveChangesAsync();
        }

        public async Task UpdateMataKuliahAsync(MataKuliah mk)
        {
            _db.MataKuliahs.Update(mk);
            await _db.SaveChangesAsync();
        }

        public async Task DeleteMataKuliahAsync(int id)
        {
            var mk = await _db.MataKuliahs.FindAsync(id);
            if (mk != null)
            {
                _db.MataKuliahs.Remove(mk);
                await _db.SaveChangesAsync();
            }
        }

        // ==================== MATERI ====================
        public async Task<List<Materi>> GetAllMateriAsync() =>
            await _db.Materis.Include(m => m.MataKuliah).OrderBy(m => m.Pertemuan).ToListAsync();

        public async Task<List<Materi>> GetMateriByMataKuliahAsync(int mataKuliahId) =>
            await _db.Materis.Include(m => m.MataKuliah)
                .Where(m => m.MataKuliahId == mataKuliahId)
                .OrderBy(m => m.Pertemuan).ToListAsync();

        public async Task<Materi?> GetMateriByIdAsync(int id) =>
            await _db.Materis.Include(m => m.MataKuliah).FirstOrDefaultAsync(m => m.Id == id);

        public async Task AddMateriAsync(Materi m) { _db.Materis.Add(m); await _db.SaveChangesAsync(); }
        public async Task UpdateMateriAsync(Materi m) { _db.Materis.Update(m); await _db.SaveChangesAsync(); }
        public async Task DeleteMateriAsync(int id)
        {
            var m = await _db.Materis.FindAsync(id);
            if (m != null) { _db.Materis.Remove(m); await _db.SaveChangesAsync(); }
        }

        // ==================== SOAL ====================
        public async Task<List<Soal>> GetAllSoalAsync() =>
            await _db.Soals.Include(s => s.Materi).ThenInclude(m => m!.MataKuliah)
                .OrderBy(s => s.MateriId).ThenBy(s => s.Id).ToListAsync();

        public async Task<List<Soal>> GetSoalByMateriAsync(int materiId) =>
            await _db.Soals.Include(s => s.Materi).ThenInclude(m => m!.MataKuliah)
                .Where(s => s.MateriId == materiId).ToListAsync();

        public async Task<List<Soal>> GetSoalByMataKuliahAsync(int mataKuliahId) =>
            await _db.Soals.Include(s => s.Materi).ThenInclude(m => m!.MataKuliah)
                .Where(s => s.Materi != null && s.Materi.MataKuliahId == mataKuliahId).ToListAsync();

        public async Task AddSoalAsync(Soal s) { _db.Soals.Add(s); await _db.SaveChangesAsync(); }
        public async Task UpdateSoalAsync(Soal s) { _db.Soals.Update(s); await _db.SaveChangesAsync(); }
        public async Task DeleteSoalAsync(int id)
        {
            var s = await _db.Soals.FindAsync(id);
            if (s != null) { _db.Soals.Remove(s); await _db.SaveChangesAsync(); }
        }

        // ==================== HASIL ====================
        public async Task<List<HasilUjian>> GetAllHasilAsync() =>
            await _db.HasilUjians.Include(h => h.User).Include(h => h.Materi)
                .OrderByDescending(h => h.SubmittedAt).ToListAsync();

        public async Task<List<HasilUjian>> GetHasilByUserAsync(int userId) =>
            await _db.HasilUjians.Include(h => h.Materi)
                .Where(h => h.UserId == userId).OrderByDescending(h => h.SubmittedAt).ToListAsync();

        public async Task AddHasilAsync(HasilUjian h) { _db.HasilUjians.Add(h); await _db.SaveChangesAsync(); }

        public async Task<int> GetSoalCountByMateriAsync(int materiId) =>
            await _db.Soals.CountAsync(s => s.MateriId == materiId);

        // ==================== DOKUMEN SOAL (PDF FULL) ====================
        public async Task<List<DokumenSoal>> GetAllDokumenSoalAsync() =>
            await _db.DokumenSoals.Include(d => d.MataKuliah)
                .OrderByDescending(d => d.TanggalUpload).ToListAsync();

        public async Task<List<DokumenSoal>> GetDokumenSoalByMatkulAsync(int matkulId)
        {
            if (matkulId == 0) return await GetAllDokumenSoalAsync();
            return await _db.DokumenSoals.Include(d => d.MataKuliah)
                .Where(d => d.MataKuliahId == matkulId)
                .OrderByDescending(d => d.TanggalUpload).ToListAsync();
        }

        public async Task AddDokumenSoalAsync(DokumenSoal doc)
        {
            _db.DokumenSoals.Add(doc);
            await _db.SaveChangesAsync();
        }

        public async Task DeleteDokumenSoalAsync(int id)
        {
            var doc = await _db.DokumenSoals.FindAsync(id);
            if (doc != null)
            {
                _db.DokumenSoals.Remove(doc);
                await _db.SaveChangesAsync();
            }
        }
    }
}
