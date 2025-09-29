<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'Docente']);
        Role::create(['name' => 'Estudiante']);
        Role::create(['name' => 'Rector']);
        Role::create(['name' => 'Coordinador']);
        Role::create(['name' => 'Secretario']);
    }
}
