<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Leer el archivo SQL de municipios
        $sqlFile = public_path('municipios.sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->error('El archivo municipios.sql no existe en la carpeta public');
            return;
        }

        $sqlContent = File::get($sqlFile);
        
        // Extraer los datos de INSERT del archivo SQL
        preg_match_all("/\((\d+),'([^']+)',(\d+)\)/", $sqlContent, $matches, PREG_SET_ORDER);
        
        $municipalities = [];
        foreach ($matches as $match) {
            $municipalities[] = [
                'id' => (int) $match[1],
                'name' => $match[2],
                'department_id' => (int) $match[3],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar en lotes para mejor rendimiento
        $chunks = array_chunk($municipalities, 100);
        
        foreach ($chunks as $chunk) {
            DB::table('municipalities')->insert($chunk);
        }

        $this->command->info('Municipios insertados: ' . count($municipalities));
    }
}
