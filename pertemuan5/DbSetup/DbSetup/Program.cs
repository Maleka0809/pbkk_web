using System;
using MySqlConnector;

namespace DbSetup
{
    class Program
    {
        static void Main(string[] args)
        {
            string connectionString = "Server=127.0.0.1;Port=3306;Uid=root;Pwd=;TreatTinyAsBoolean=false;";

            string[] commands = new string[]
            {
                "CREATE DATABASE IF NOT EXISTS studentdb",
                "USE studentdb",
                @"CREATE TABLE IF NOT EXISTS Students
                (
                    Id INT AUTO_INCREMENT PRIMARY KEY,
                    NIM VARCHAR(20) NOT NULL,
                    Nama VARCHAR(100) NOT NULL,
                    Jurusan VARCHAR(100) NOT NULL,
                    Gender VARCHAR(20) NOT NULL,
                    Email VARCHAR(100)
                )",
                "DELETE FROM studentdb.Students", // hapus data lama supaya tidak duplikat
                @"INSERT INTO studentdb.Students (NIM, Nama, Jurusan, Gender, Email) VALUES
                ('23001','Budi Santoso','Informatika','Laki-laki','budi@gmail.com'),
                ('23002','Siti Aminah','Sistem Informasi','Perempuan','siti@gmail.com'),
                ('23003','Andi Wijaya','Informatika','Laki-laki','andi@gmail.com'),
                ('23004','Rina Sari','Sistem Informasi','Perempuan','rina@gmail.com')"
            };

            try
            {
                using (MySqlConnection conn = new MySqlConnection(connectionString))
                {
                    conn.Open();
                    Console.WriteLine("[OK] Terhubung ke MySQL di port 3306");

                    foreach (var sql in commands)
                    {
                        using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                        {
                            cmd.ExecuteNonQuery();
                            Console.WriteLine("[OK] " + sql.Trim().Split('\n')[0].Trim());
                        }
                    }

                    Console.WriteLine("\n[SUKSES] Database 'studentdb' dan tabel 'Students' berhasil dibuat + diisi data sample!");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("[ERROR] " + ex.Message);
            }
        }
    }
}
