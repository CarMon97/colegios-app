<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log para debug - ver qué datos llegan
        Log::info('Datos de fila recibidos:', $row);
        Log::info('Claves disponibles en la fila:', array_keys($row));

        // Verificar si la fila está vacía o es solo headers
        if (empty($row) || (count($row) === 1 && isset($row['name']) && $row['name'] === 'name')) {
            Log::info('Saltando fila vacía o de headers');
            return null;
        }

        // Validar que todas las columnas requeridas estén presentes
        $requiredColumns = ['name', 'last_name', 'email', 'gender', 'address', 'birth_date', 'password', 'type_document_id', 'municipality_id'];

        foreach ($requiredColumns as $column) {
            if (!isset($row[$column]) || empty($row[$column])) {
                Log::error("Columna faltante o vacía: {$column}", ['row' => $row]);
                throw new \Exception("La columna '{$column}' es requerida y no puede estar vacía. Datos recibidos: " . json_encode($row));
            }
        }

        $user = User::create([
            'name' => $row['name'],
            'last_name' => $row['last_name'],
            'identification' => $row['identification'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'address' => $row['address'],
            'birth_date' => $row['birth_date'],
            'password' => Hash::make($row['password']),
            'type_document_id' => $row['type_document_id'],
            'municipality_id' => $row['municipality_id'],
        ]);

        $user->assignRole('Estudiante');

        return $user;
    }
}
