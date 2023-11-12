<?php

namespace Database\Seeders;

use App\Models\Paquete;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaqueteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paquete::create([
            'photo_path' => 'https://aws-sw1.s3.amazonaws.com/paquete1.jpg',
            'codigo_rastreo' => 'RF547031698US',
            'peso' => '10',
            'cliente_id' => '1',
            'empleado_id' => '2',
            'almacen_id' => '1',
        ]);
    }
}
