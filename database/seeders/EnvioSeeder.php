<?php

namespace Database\Seeders;

use App\Models\Envio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Envio::create([
            'codigo_rastreo' => 'RB326878831US',
            'costo' => '350',
            'paquete_id' => '1',
            'metodo_envio_id' => '2',
            'envio_estado_id' => '1',
        ]);
    }
}
