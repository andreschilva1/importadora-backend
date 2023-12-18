<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $role1 = Role::create(['name' => 'Personal Administrativo']);
        $role2 = Role::create(['name' => 'Encargado de Almacen']);
        $role3 = Role::create(['name' => 'Encargado de Envio']);
        $role4 = Role::create(['name' => 'Encargado de compra']);
        $role5 = Role::create(['name' => 'Cliente']);
                
    }
}
