<?php

namespace Database\Seeders;

use App\Models\ConsolidacionEstado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsolidacionEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConsolidacionEstado::create([
            'name' => 'En consolidacion',
        ]);

        ConsolidacionEstado::create([
            'name' => 'Terminado',
        ]);
    }
}
