using System.Windows;
using StudentRegistration.Models;
using StudentRegistration.Repositories;

namespace StudentRegistration;

public partial class StudentFormWindow : Window
{
    private readonly StudentRepository _studentRepository;
    private readonly ProgramRepository _programRepository;
    private readonly Student? _existingStudent;

    public StudentFormWindow(Student? student = null)
    {
        InitializeComponent();
        _studentRepository = new StudentRepository();
        _programRepository = new ProgramRepository();
        _existingStudent = student;

        LoadPrograms();

        if (_existingStudent != null)
        {
            Title = "Edit Student";
            NimTextBox.Text = _existingStudent.NIM;
            NameTextBox.Text = _existingStudent.Name;
            ProgramComboBox.SelectedValue = _existingStudent.ProgramId;
            BirthDatePicker.SelectedDate = _existingStudent.BirthDate;
            AddressTextBox.Text = _existingStudent.Address;
            PhoneTextBox.Text = _existingStudent.PhoneNumber;
        }
        else
        {
            Title = "Tambah Student";
        }
    }

    private void LoadPrograms()
    {
        ProgramComboBox.ItemsSource = _programRepository.GetAll();
    }

    private void SaveButton_Click(object sender, RoutedEventArgs e)
    {
        if (ProgramComboBox.SelectedValue == null)
        {
            MessageBox.Show("Pilih Program Studi terlebih dahulu.", "Peringatan", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }

        var student = new Student
        {
            NIM = NimTextBox.Text,
            Name = NameTextBox.Text,
            ProgramId = (int)ProgramComboBox.SelectedValue,
            BirthDate = BirthDatePicker.SelectedDate,
            Address = AddressTextBox.Text,
            PhoneNumber = PhoneTextBox.Text
        };

        if (_existingStudent == null)
        {
            _studentRepository.Add(student);
        }
        else
        {
            student.StudentId = _existingStudent.StudentId;
            _studentRepository.Update(student);
        }

        DialogResult = true;
        Close();
    }

    private void CancelButton_Click(object sender, RoutedEventArgs e)
    {
        DialogResult = false;
        Close();
    }
}
