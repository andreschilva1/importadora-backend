<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empleado::create([
            'user_id' => '2',
            'almacen_id' => '1',
        ]);

        Empleado::create([
            'user_id' => '4',
            'almacen_id' => '1',
        ]);
        
        Empleado::create([
            'user_id' => '5',
            'almacen_id' => '1',
        ]);
        
    }
}
