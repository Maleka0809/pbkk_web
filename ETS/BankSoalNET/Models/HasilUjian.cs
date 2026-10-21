using System;

namespace BankSoalNET.Models
{
    public class HasilUjian
    {
        public int Id { get; set; }
        public int UserId { get; set; }
        public int MateriId { get; set; }
        public int Nilai { get; set; }
        public int PgBenar { get; set; }
        public int PgTotal { get; set; }
        public int EssaySkor { get; set; }
        public int EssayTotal { get; set; }
        public DateTime SubmittedAt { get; set; } = DateTime.Now;

        public User? User { get; set; }
        public Materi? Materi { get; set; }
    }
}
