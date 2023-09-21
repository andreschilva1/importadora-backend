<?php

namespace Database\Seeders;

use App\Models\Almacen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Almacen::create([
            'name' => 'Alan Fergusson',
            'direccion' => '8216 NW 68th St Miami, FL 33166',
            'telefono' => '123456789',
            'pais'=> 'USA',
        ]);
    }
}
