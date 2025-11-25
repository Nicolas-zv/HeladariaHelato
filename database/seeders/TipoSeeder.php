<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tipo;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tipos = [
            ['cod_tipo' => 'T001', 'descripcion' => 'Helado'],
            ['cod_tipo' => 'T002', 'descripcion' => 'Bebida'],
            ['cod_tipo' => 'T003', 'descripcion' => 'Postre'],
            ['cod_tipo' => 'T004', 'descripcion' => 'Snack'],
            ['cod_tipo' => 'T005', 'descripcion' => 'Combo'],
        ];

        foreach ($tipos as $tipo) {
            Tipo::firstOrCreate(
                ['cod_tipo' => $tipo['cod_tipo']],
                ['descripcion' => $tipo['descripcion']]
            );
        }
    }
}