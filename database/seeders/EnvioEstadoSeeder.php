<?php

namespace Database\Seeders;

use App\Models\EnvioEstado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvioEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnvioEstado::create([
            'name' => 'Esperando al transportista',
        ]);

        EnvioEstado::create([
            'name' => 'En camino',
        ]);

        EnvioEstado::create([
            'name' => 'listo para entregar',
        ]);

        EnvioEstado::create([
            'name' => 'Entregado',
        ]);
    }
}
