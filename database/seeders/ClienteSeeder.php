<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'numero_casillero' => 'A4434',
            'token_android' => null,
            'user_id' => '1',
        ]);

        Cliente::create([
            'numero_casillero' => 'L64120',
            'token_android' => null,
            'user_id' => '6',
        ]);

        Cliente::create([
            'numero_casillero' => 'J73214',
            'token_android' => null,
            'user_id' => '7',
        ]);

        Cliente::create([
            'numero_casillero' => 'C84632',
            'token_android' => null,
            'user_id' => '8',
        ]);

        Cliente::create([
            'numero_casillero' => 'L92139',
            'token_android' => null,
            'user_id' => '9',
        ]);

        Cliente::create([
            'numero_casillero' => 'M101435',
            'token_android' => null,
            'user_id' => '10',
        ]);

    }
}
