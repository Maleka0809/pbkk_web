using System;
using System.Windows;
using StudentRegistration.Models;
using StudentRegistration.Repositories;

namespace StudentRegistration;

public partial class MainWindow : Window
{
    private readonly StudentRepository _studentRepository;

    public MainWindow()
    {
        InitializeComponent();
        _studentRepository = new StudentRepository();
        LoadStudents();
    }

    private void LoadStudents()
    {
        try
        {
            var students = _studentRepository.GetAll();
            StudentDataGrid.ItemsSource = students;
        }
        catch (Exception ex)
        {
            MessageBox.Show(
                ex.Message,
                "Database Error",
                MessageBoxButton.OK,
                MessageBoxImage.Error);
        }
    }

    private void RefreshButton_Click(object sender, RoutedEventArgs e)
    {
        LoadStudents();
    }

    private void AddButton_Click(object sender, RoutedEventArgs e)
    {
        var form = new StudentFormWindow();
        form.Owner = this;
        if (form.ShowDialog() == true)
        {
            LoadStudents();
        }
    }

    private void EditButton_Click(object sender, RoutedEventArgs e)
    {
        if (StudentDataGrid.SelectedItem is Student selectedStudent)
        {
            var form = new StudentFormWindow(selectedStudent);
            form.Owner = this;
            if (form.ShowDialog() == true)
            {
                LoadStudents();
            }
        }
        else
        {
            MessageBox.Show("Pilih mahasiswa yang ingin diedit terlebih dahulu.", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
        }
    }

    private void DeleteButton_Click(object sender, RoutedEventArgs e)
    {
        if (StudentDataGrid.SelectedItem is Student selectedStudent)
        {
            var result = MessageBox.Show($"Apakah Anda yakin ingin menghapus {selectedStudent.Name}?", "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Question);
            if (result == MessageBoxResult.Yes)
            {
                _studentRepository.Delete(selectedStudent.StudentId);
                LoadStudents();
            }
        }
        else
        {
            MessageBox.Show("Pilih mahasiswa yang ingin dihapus terlebih dahulu.", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
        }
    }
}