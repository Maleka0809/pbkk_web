using System;

namespace BankSoalNET.Models
{
    public class User
    {
        public int Id { get; set; }
        public string Username { get; set; } = string.Empty;
        public string Password { get; set; } = string.Empty;
        public string NamaLengkap { get; set; } = string.Empty;
        public string Role { get; set; } = "mahasiswa"; // "admin" | "mahasiswa"
        public DateTime CreatedAt { get; set; } = DateTime.Now;
    }
}
