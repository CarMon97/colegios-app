<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['id' => 5, 'name' => 'Antioquia'],
            ['id' => 8, 'name' => 'Atlantico'],
            ['id' => 11, 'name' => 'Bogota'],
            ['id' => 13, 'name' => 'Bolivar'],
            ['id' => 15, 'name' => 'Boyaca'],
            ['id' => 17, 'name' => 'Caldas'],
            ['id' => 18, 'name' => 'Caqueta'],
            ['id' => 19, 'name' => 'Cauca'],
            ['id' => 20, 'name' => 'Cesar'],
            ['id' => 23, 'name' => 'Cordoba'],
            ['id' => 25, 'name' => 'Cundinamarca'],
            ['id' => 27, 'name' => 'Choco'],
            ['id' => 41, 'name' => 'Huila'],
            ['id' => 44, 'name' => 'La Guajira'],
            ['id' => 47, 'name' => 'Magdalena'],
            ['id' => 50, 'name' => 'Meta'],
            ['id' => 52, 'name' => 'Nariño'],
            ['id' => 54, 'name' => 'N. De Santander'],
            ['id' => 63, 'name' => 'Quindio'],
            ['id' => 66, 'name' => 'Risaralda'],
            ['id' => 68, 'name' => 'Santander'],
            ['id' => 70, 'name' => 'Sucre'],
            ['id' => 73, 'name' => 'Tolima'],
            ['id' => 76, 'name' => 'Valle Del Cauca'],
            ['id' => 81, 'name' => 'Arauca'],
            ['id' => 85, 'name' => 'Casanare'],
            ['id' => 86, 'name' => 'Putumayo'],
            ['id' => 88, 'name' => 'San Andres'],
            ['id' => 91, 'name' => 'Amazonas'],
            ['id' => 94, 'name' => 'Guainia'],
            ['id' => 95, 'name' => 'Guaviare'],
            ['id' => 97, 'name' => 'Vaupes'],
            ['id' => 99, 'name' => 'Vichada'],
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'id' => $department['id'],
                'name' => $department['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
