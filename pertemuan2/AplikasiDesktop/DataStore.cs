using System.Collections.Generic;

namespace AplikasiDesktop;

public class Mahasiswa
{
    public string NIM { get; set; }
    public string Nama { get; set; }
    public string Prodi { get; set; }
    public double IPK { get; set; }

    public Mahasiswa(string nim, string nama, string prodi, double ipk)
    {
        NIM = nim;
        Nama = nama;
        Prodi = prodi;
        IPK = ipk;
    }
}

public static class DataStore
{
    public static List<Mahasiswa> DaftarMahasiswa = new List<Mahasiswa>();
}
