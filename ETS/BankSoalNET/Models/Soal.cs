using System;
using System.Collections.Generic;

namespace BankSoalNET.Models
{
    public enum TipeSoal { PilihanGanda, Essay }

    public class Soal
    {
        public int Id { get; set; }
        public int MateriId { get; set; }
        public TipeSoal Tipe { get; set; }
        public string Pertanyaan { get; set; } = string.Empty;

        // Pilihan Ganda
        public string? PilihanA { get; set; }
        public string? PilihanB { get; set; }
        public string? PilihanC { get; set; }
        public string? PilihanD { get; set; }
        public int JawabanBenar { get; set; } // 0=A,1=B,2=C,3=D

        // Essay
        public string? KataKunci { get; set; } // comma separated

        // Media & Dokumen Lampiran
        public string? GambarPath { get; set; }  // Path file PNG/JPG lokal
        public string? GambarDesc { get; set; }
        public string? PdfPath { get; set; }     // Path file PDF lokal
        public string? PdfName { get; set; }     // Nama file PDF

        // Pembahasan Kunci Jawaban
        public string? Pembahasan { get; set; }

        public DateTime CreatedAt { get; set; } = DateTime.Now;

        public Materi? Materi { get; set; }

        public string[] GetPilihan() => new[] { PilihanA ?? "", PilihanB ?? "", PilihanC ?? "", PilihanD ?? "" };
        public string[] GetKataKunci() => KataKunci?.Split(',', StringSplitOptions.RemoveEmptyEntries) ?? Array.Empty<string>();
        public bool HasGambar => !string.IsNullOrEmpty(GambarPath);
        public bool HasPdf => !string.IsNullOrEmpty(PdfPath);
        public string JawabanBenarText => JawabanBenar switch
        {
            0 => $"A. {PilihanA}",
            1 => $"B. {PilihanB}",
            2 => $"C. {PilihanC}",
            3 => $"D. {PilihanD}",
            _ => "-"
        };
    }
}
