<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('12345678');

        User::create([
            'name' => 'Andres',
            'email' => 'wasulwasol@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '69164434',
        ])->assignRole('Cliente');

        User::create([
            'name' => 'Chile',
            'email' => 'chile@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Juan Mendoza Pereira',
            'email' => 'juan@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '75174852',
        ])->assignRole('Admin');

        User::create([
            'name' => 'Chile',
            'email' => 'Eduardo@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Chile',
            'email' => 'pepe@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Chile',
            'email' => 'lucas@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Chile',
            'email' => 'jerardo@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');
        User::create([
            'name' => 'Chile',
            'email' => 'carlos@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');
        User::create([
            'name' => 'Chile',
            'email' => 'lucho@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');
        
        User::create([
            'name' => 'Chile',
            'email' => 'loquito@gmail.com',
            'password' => $password,
            'profile_photo_path' => null,
            'celular' => '65152340',
        ])->assignRole('Empleado');
                
    }
}
