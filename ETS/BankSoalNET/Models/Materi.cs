using System;
using System.Collections.Generic;

namespace BankSoalNET.Models
{
    public class Materi
    {
        public int Id { get; set; }
        public int MataKuliahId { get; set; } = 1;
        public int Pertemuan { get; set; }
        public string Judul { get; set; } = string.Empty;
        public string Deskripsi { get; set; } = string.Empty;
        public string Konten { get; set; } = string.Empty;
        public string Tags { get; set; } = string.Empty; // comma separated
        public DateTime CreatedAt { get; set; } = DateTime.Now;

        public MataKuliah? MataKuliah { get; set; }
        public ICollection<Soal> Soals { get; set; } = new List<Soal>();
    }
}
