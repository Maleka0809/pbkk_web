using BankSoalNET.Models;
using Microsoft.EntityFrameworkCore;
using System;
using System.IO;

namespace BankSoalNET.Data
{
    public class AppDbContext : DbContext
    {
        public DbSet<User> Users => Set<User>();
        public DbSet<MataKuliah> MataKuliahs => Set<MataKuliah>();
        public DbSet<Materi> Materis => Set<Materi>();
        public DbSet<Soal> Soals => Set<Soal>();
        public DbSet<HasilUjian> HasilUjians => Set<HasilUjian>();
        public DbSet<DokumenSoal> DokumenSoals => Set<DokumenSoal>();

        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            string dbPath = Path.Combine(
                Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData),
                "BankSoalNET", "banksoal.db");
            Directory.CreateDirectory(Path.GetDirectoryName(dbPath)!);
            optionsBuilder.UseSqlite($"Data Source={dbPath}");
        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Soal>()
                .Property(s => s.Tipe)
                .HasConversion<string>();

            modelBuilder.Entity<Materi>()
                .HasOne(m => m.MataKuliah)
                .WithMany(mk => mk.Materis)
                .HasForeignKey(m => m.MataKuliahId)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<Soal>()
                .HasOne(s => s.Materi)
                .WithMany(m => m.Soals)
                .HasForeignKey(s => s.MateriId)
                .OnDelete(DeleteBehavior.Cascade);

            modelBuilder.Entity<DokumenSoal>()
                .HasOne(d => d.MataKuliah)
                .WithMany(mk => mk.DokumenSoals)
                .HasForeignKey(d => d.MataKuliahId)
                .OnDelete(DeleteBehavior.Cascade);

            base.OnModelCreating(modelBuilder);
        }
    }
}
