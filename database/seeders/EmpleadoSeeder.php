<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder crea registros en la tabla `empleados` utilizando Personas ya existentes.
     * - Si no hay personas en la tabla `personas`, no hará nada (evita crear datos inconsistentes).
     * - Usa updateOrInsert para ser idempotente: puede ejecutarse varias veces sin duplicados.
     *
     * Si preferís crear empleados a partir de datos concretos (nombres/dni) o crear
     * las personas automáticamente, avisame y lo adapto.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Tomamos hasta 10 personas existentes para crear empleados (ajustá el límite si querés)
        $personas = DB::table('personas')->limit(10)->get();

        if ($personas->isEmpty()) {
            // No hay personas para vincular; no hacer nada para evitar datos huérfanos.
            // Si querés que el seeder cree también personas de ejemplo, lo puedo agregar.
            return;
        }

        foreach ($personas as $index => $persona) {
            // Construimos datos por defecto; podés personalizarlos aquí.
            $direccion = null;
            // Ejemplo: si la persona tiene campo 'direccion' lo reutilizamos
            if (isset($persona->direccion) && $persona->direccion) {
                $direccion = $persona->direccion;
            } else {
                // Dirección por defecto basada en índice (puedes cambiarlo)
                $direccion = 'Sin dirección registrada';
            }

            DB::table('empleados')->updateOrInsert(
                ['persona_id' => $persona->id],
                [
                    'direccion' => $direccion,
                    'users_id' => null, // Asignar user_id si tienes usuarios relacionados
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
