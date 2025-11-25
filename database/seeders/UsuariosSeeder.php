<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Castedo Suárez Pablo Daniel',
            'email' => 'daniel@gmail.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Administrador');

         User::create([
            'name' => 'Yaryta Quispe Montaño',
            'email' => 'yaryta@gmail.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Administrador');

         User::create([
            'name' => 'Nicolas',
            'email' => 'nicolas@gmail.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('Administrador');

         User::create([
            'name' => 'Maddy',
            'email' => 'maddy@gmail.com',
            'password' => Hash::make('123456789'),
        ])->assignRole('Administrador');

    }
}
