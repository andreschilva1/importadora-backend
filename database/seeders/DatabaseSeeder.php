<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Almacen;
use App\Models\Empleado;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //\App\Models\User::factory(30)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(MetodoEnvioSeeder::class);
        $this->call(AlmacenSeeder::class);
        $this->call(PaqueteSeeder::class);
        $this->call(EnvioEstadoSeeder::class);
        $this->call(EnvioSeeder::class);
        $this->call(EmpleadoSeeder::class);
    }
}
