<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sabor;

class SaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sabores = [
            'Chocolate',
            'Vainilla',
            'Frutilla',
            'Menta',
            'Café',
            'Coco',
        ];

        foreach ($sabores as $sabor) {
            Sabor::firstOrCreate(['descripcion' => $sabor]);
        }
    }
}