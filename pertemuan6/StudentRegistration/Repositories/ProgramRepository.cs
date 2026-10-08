using Microsoft.Data.SqlClient;
using StudentRegistration.Models;
using System.Collections.Generic;

namespace StudentRegistration.Repositories;

public class ProgramRepository
{
    private readonly string _connectionString =
        @"Server=.\SQLEXPRESS;
        Database=StudentRegistrationDB;
        Trusted_Connection=True;
        TrustServerCertificate=True;";

    public List<Program> GetAll()
    {
        var programs = new List<Program>();
        const string sql = "SELECT ProgramId, ProgramCode, ProgramName, IsActive FROM Programs WHERE IsActive = 1 ORDER BY ProgramName;";

        using var connection = new SqlConnection(_connectionString);
        using var command = new SqlCommand(sql, connection);
        
        connection.Open();
        using var reader = command.ExecuteReader();
        
        while (reader.Read())
        {
            programs.Add(new Program
            {
                ProgramId = reader.GetInt32(0),
                ProgramCode = reader.GetString(1),
                ProgramName = reader.GetString(2),
                IsActive = reader.GetBoolean(3)
            });
        }
        
        return programs;
    }
}
