<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear roles
        $role1 = Role::firstOrCreate(['name' => 'Administrador']);
        $role2 = Role::firstOrCreate(['name' => 'Secretario']);
        $role3 = Role::firstOrCreate(['name' => 'Cajero']);
        $role4 = Role::firstOrCreate(['name' => 'Cocinero']);
        $role5 = Role::firstOrCreate(['name' => 'Mesero']);

        // Crear permisos y asignarlos a roles correspondientes
        // Administrador
        Permission::create(['name' => 'administrador.user'])->syncRoles($role1);
        Permission::create(['name' => 'administrador.crear'])->syncRoles($role1);
        Permission::create(['name' => 'administrador.editar'])->syncRoles($role1);
        Permission::create(['name' => 'administrador.eliminar'])->syncRoles($role1);

        // Secretaria
        Permission::create(['name' => 'secretaria.crear'])->syncRoles($role2);
        Permission::create(['name' => 'secretaria.editar'])->syncRoles($role2);
        Permission::create(['name' => 'secretaria.eliminar'])->syncRoles($role2);

        // Cajera
        Permission::create(['name' => 'cajera.crear'])->syncRoles($role3);
        Permission::create(['name' => 'cajera.editar'])->syncRoles($role3);

        // Cocinero
        Permission::create(['name' => 'cocinero'])->syncRoles($role4);

        // Mesero
        Permission::create(['name' => 'mesero.crear'])->syncRoles($role5);
        Permission::create(['name' => 'mesero.ver'])->syncRoles($role5);
    }
}
