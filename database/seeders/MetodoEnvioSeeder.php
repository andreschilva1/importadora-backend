<?php

namespace Database\Seeders;

use App\Models\MetodoEnvio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodoEnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetodoEnvio::create([
            'transportista' => 'BOA',
            'metodo' => 'aereo',
            'costo_kg' => '25',
            'pais_id' => '1',
        ]);

        MetodoEnvio::create([
            'transportista' => 'TAB',
            'metodo' => 'aereo',
            'costo_kg' => '35',
            'pais_id' => '1',
        ]);
    }
}
