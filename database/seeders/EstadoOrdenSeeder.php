<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadoOrdenSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $estados = [
            [
                'nombre' => 'Pendiente',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'En proceso',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Servido',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($estados as $e) {
            DB::table('estado_ordenes')->updateOrInsert(
                ['nombre' => $e['nombre']],
                ['created_at' => $e['created_at'], 'updated_at' => $e['updated_at']]
            );
        }
    }
}