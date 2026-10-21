# ETS_PBKK — Bank Soal .NET (WPF Desktop Application)

Aplikasi desktop modern berbasis **WPF (.NET 8 C#)** untuk evaluasi pembelajaran, bank soal, dan modul studi kasus pada mata kuliah **Pemrograman Berbasis Kerangka Kerja (PBKK)**.

---

## 🚀 Fitur Utama

### 1. Autentikasi & Manajemen Pengguna
* **Unified Login**: Satu pintu masuk untuk Mahasiswa dan Admin. Sistem secara otomatis mendeteksi peran pengguna dari database dan mengarahkan ke antarmuka yang sesuai.
* **Fitur Registrasi**: Pendaftaran akun baru Mahasiswa atau Admin/Dosen dengan validasi lengkap.
* **Quick Fill Demo**: Tombol cepat untuk mengisi akun demo admin (`admin`/`admin123`) atau mahasiswa (`mhs01`/`mhs123`).

### 2. Hierarki Mata Kuliah & Materi
* Pengelompokan materi dan soal berdasarkan **Mata Kuliah** (contoh: *PBKK .NET, Pemrograman Web Modern, Basis Data*).
* Admin dapat menambah, mengubah, dan menghapus Mata Kuliah beserta materi pembelajarannya.

### 3. Bank Soal & Kunci Jawaban
* **Katalog Interaktif**: Melihat seluruh kumpulan soal (Pilihan Ganda & Essay), kunci jawaban, dan pembahasan mendalam tanpa harus mengerjakan ujian.
* **Diagram PNG/JPG**: Dukungan gambar diagram arsitektur/skema untuk soal visual.

### 4. Dokumen Bank Soal (PDF Full Reader)
* **Embedded PDF Reader**: Membaca dokumen kisi-kisi dan modul soal PDF langsung di dalam aplikasi secara penuh menggunakan **Microsoft WebView2** (zoom, scroll, search, dan print).
* **Upload PDF Dokumen**: Admin dapat mengunggah berkas PDF baru per mata kuliah.
* **Layar Penuh Eksternal**: Tombol pintas untuk membuka PDF di browser Edge / Adobe Acrobat.

### 5. Evaluasi & Ujian Mahasiswa
* **Mode Belajar**: Mempelajari materi kuliah per pertemuan.
* **Mode Scoring (Ujian)**: Mengerjakan kuis interaktif dengan timer, auto-grading PG, dan penilaian kata kunci essay.
* **Riwayat & Rekap Nilai**: Mahasiswa dapat melihat histori skor pengerjaan, dan Admin dapat melihat rekapitulasi nilai seluruh mahasiswa.

---

## 🛠️ Teknologi yang Digunakan
* **Platform**: Windows Desktop (WPF - Windows Presentation Foundation)
* **Framework**: .NET 8.0 (`net8.0-windows`)
* **Bahasa**: C# 12
* **Arsitektur**: MVVM (Model-View-ViewModel) dengan CommunityToolkit.Mvvm
* **Desain UI**: Material Design in XAML Toolkit
* **Database**: SQLite (Serverless / In-Process) via Entity Framework Core 8
* **PDF Engine**: Microsoft Edge WebView2

---

## 💻 Cara Menjalankan Aplikasi

### Cara 1: Menggunakan Shortcut Batch
Klik ganda file:
```cmd
Buka-Aplikasi.bat
```

### Cara 2: Menggunakan .NET CLI
Buka Terminal/PowerShell di folder proyek:
```powershell
cd BankSoalNET
dotnet run
```

---

## 👤 Akun Bawaan (Default Seed)
* **Admin / Dosen**: `admin` / `admin123`
* **Mahasiswa**: `mhs01` / `mhs123` atau `mhs02` / `mhs123`
