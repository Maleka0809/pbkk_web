using System;
using System.Collections.Generic;

namespace BankSoalNET.Models
{
    public class MataKuliah
    {
        public int Id { get; set; }
        public string Kode { get; set; } = string.Empty;
        public string Nama { get; set; } = string.Empty;
        public string Singkatan { get; set; } = string.Empty;
        public int Sks { get; set; } = 3;
        public int Semester { get; set; } = 4;
        public string Icon { get; set; } = "📚";
        public string Warna { get; set; } = "purple"; // purple, cyan, orange
        public string Deskripsi { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; } = DateTime.Now;

        public ICollection<Materi> Materis { get; set; } = new List<Materi>();
        public ICollection<DokumenSoal> DokumenSoals { get; set; } = new List<DokumenSoal>();
    }
}
