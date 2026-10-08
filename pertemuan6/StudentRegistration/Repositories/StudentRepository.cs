using Microsoft.Data.SqlClient;
using StudentRegistration.Models;
using System;
using System.Collections.Generic;

namespace StudentRegistration.Repositories;

public class StudentRepository
{
    private readonly string _connectionString =
        @"Server=.\SQLEXPRESS;
        Database=StudentRegistrationDB;
        Trusted_Connection=True;
        TrustServerCertificate=True;";

    public List<Student> GetAll()
    {
        var students = new List<Student>();
        const string sql = """
            SELECT 
                s.StudentId,
                s.NIM,
                s.Name,
                s.ProgramId,
                p.ProgramName,
                s.BirthDate,
                s.Address,
                s.PhoneNumber,
                s.CreatedAt,
                s.UpdatedAt
            FROM Students s
            INNER JOIN Programs p
                ON s.ProgramId = p.ProgramId
            ORDER BY s.StudentId;
            """;

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(sql, connection);
        
        connection.Open();
        
        using var reader = command.ExecuteReader();
        
        while (reader.Read())
        {
            students.Add(new Student
            {
                StudentId = reader.GetInt32(reader.GetOrdinal("StudentId")),
                NIM = reader.GetString(reader.GetOrdinal("NIM")),
                Name = reader.GetString(reader.GetOrdinal("Name")),
                ProgramId = reader.GetInt32(reader.GetOrdinal("ProgramId")),
                ProgramName = reader.GetString(reader.GetOrdinal("ProgramName")),
                BirthDate = reader.IsDBNull(reader.GetOrdinal("BirthDate")) 
                    ? null 
                    : reader.GetDateTime(reader.GetOrdinal("BirthDate")),
                Address = reader.IsDBNull(reader.GetOrdinal("Address")) 
                    ? string.Empty 
                    : reader.GetString(reader.GetOrdinal("Address")),
                PhoneNumber = reader.IsDBNull(reader.GetOrdinal("PhoneNumber")) 
                    ? string.Empty 
                    : reader.GetString(reader.GetOrdinal("PhoneNumber")),
                CreatedAt = reader.GetDateTime(reader.GetOrdinal("CreatedAt")),
                UpdatedAt = reader.IsDBNull(reader.GetOrdinal("UpdatedAt")) 
                    ? null 
                    : reader.GetDateTime(reader.GetOrdinal("UpdatedAt"))
            });
        }
        
        return students;
    }

    public void Add(Student student)
    {
        const string sql = """
            INSERT INTO Students (NIM, Name, ProgramId, BirthDate, Address, PhoneNumber, CreatedAt)
            VALUES (@NIM, @Name, @ProgramId, @BirthDate, @Address, @PhoneNumber, GETDATE());
            """;

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(sql, connection);

        command.Parameters.AddWithValue("@NIM", student.NIM);
        command.Parameters.AddWithValue("@Name", student.Name);
        command.Parameters.AddWithValue("@ProgramId", student.ProgramId);
        command.Parameters.AddWithValue("@BirthDate", (object?)student.BirthDate ?? DBNull.Value);
        command.Parameters.AddWithValue("@Address", (object?)student.Address ?? DBNull.Value);
        command.Parameters.AddWithValue("@PhoneNumber", (object?)student.PhoneNumber ?? DBNull.Value);

        connection.Open();
        command.ExecuteNonQuery();
    }

    public void Update(Student student)
    {
        const string sql = """
            UPDATE Students 
            SET NIM = @NIM, 
                Name = @Name, 
                ProgramId = @ProgramId, 
                BirthDate = @BirthDate, 
                Address = @Address, 
                PhoneNumber = @PhoneNumber,
                UpdatedAt = GETDATE()
            WHERE StudentId = @StudentId;
            """;

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(sql, connection);

        command.Parameters.AddWithValue("@StudentId", student.StudentId);
        command.Parameters.AddWithValue("@NIM", student.NIM);
        command.Parameters.AddWithValue("@Name", student.Name);
        command.Parameters.AddWithValue("@ProgramId", student.ProgramId);
        command.Parameters.AddWithValue("@BirthDate", (object?)student.BirthDate ?? DBNull.Value);
        command.Parameters.AddWithValue("@Address", (object?)student.Address ?? DBNull.Value);
        command.Parameters.AddWithValue("@PhoneNumber", (object?)student.PhoneNumber ?? DBNull.Value);

        connection.Open();
        command.ExecuteNonQuery();
    }

    public void Delete(int studentId)
    {
        const string sql = "DELETE FROM Students WHERE StudentId = @StudentId;";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(sql, connection);

        command.Parameters.AddWithValue("@StudentId", studentId);

        connection.Open();
        command.ExecuteNonQuery();
    }
}
