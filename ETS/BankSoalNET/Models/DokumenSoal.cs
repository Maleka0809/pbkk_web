using System;

namespace BankSoalNET.Models
{
    public class DokumenSoal
    {
        public int Id { get; set; }
        public int MataKuliahId { get; set; }
        public string Judul { get; set; } = string.Empty;
        public string Deskripsi { get; set; } = string.Empty;
        public string PdfPath { get; set; } = string.Empty;
        public string PdfName { get; set; } = string.Empty;
        public string UkuranFile { get; set; } = string.Empty;
        public DateTime TanggalUpload { get; set; } = DateTime.Now;

        public virtual MataKuliah? MataKuliah { get; set; }

        public string TanggalUploadFormatted => TanggalUpload.ToString("dd MMM yyyy, HH:mm");
        public string MatkulNama => MataKuliah?.Nama ?? "Umum";
        public string MatkulKode => MataKuliah?.Kode ?? "-";
    }
}
