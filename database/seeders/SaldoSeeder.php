<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaldoSeeder extends Seeder
{
    public function run()
    {
        DB::table('saldos')->insert([
            'efectivo' => 200, // Saldo inicial de efectivo
            'qr' => 100, // Saldo inicial de QR
        ]);
    }
}
