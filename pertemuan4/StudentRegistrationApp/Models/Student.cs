using System;
using System.ComponentModel;

namespace StudentRegistrationApp.Models
{
    public class Student : INotifyPropertyChanged
    {
        private string _nim;
        private string _nama;
        private string _prodi;
        private string _jenisKelamin;
        private DateTime? _tanggalLahir;
        private string _alamat;
        private string _noTelepon;

        public string Nim
        {
            get => _nim;
            set { _nim = value; OnPropertyChanged(nameof(Nim)); }
        }

        public string Nama
        {
            get => _nama;
            set { _nama = value; OnPropertyChanged(nameof(Nama)); }
        }

        public string Prodi
        {
            get => _prodi;
            set { _prodi = value; OnPropertyChanged(nameof(Prodi)); }
        }

        public string JenisKelamin
        {
            get => _jenisKelamin;
            set { _jenisKelamin = value; OnPropertyChanged(nameof(JenisKelamin)); }
        }

        public DateTime? TanggalLahir
        {
            get => _tanggalLahir;
            set { _tanggalLahir = value; OnPropertyChanged(nameof(TanggalLahir)); }
        }

        public string Alamat
        {
            get => _alamat;
            set { _alamat = value; OnPropertyChanged(nameof(Alamat)); }
        }

        public string NoTelepon
        {
            get => _noTelepon;
            set { _noTelepon = value; OnPropertyChanged(nameof(NoTelepon)); }
        }

        public event PropertyChangedEventHandler PropertyChanged;
        protected virtual void OnPropertyChanged(string propertyName)
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }
    }
}
