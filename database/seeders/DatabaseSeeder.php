<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Tipo;
use Database\Seeders\PuntosSeeder as SeedersPuntosSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PuntosSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            // DashboardTableSeeder::class,
            RoleSeeder::class,
            UsuariosSeeder::class,
            TipoSeeder::class,
            SaborSeeder::class,
            MenuSeeder::class,
            EstadoOrdenSeeder::class,
            EmpleadoSeeder::class,
            // SaldoSeeder::class,
            // SeedersPuntosSeeder::class,
        ]);
    }
}
