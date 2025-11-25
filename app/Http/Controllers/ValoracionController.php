<?php

namespace App\Http\Controllers;

use App\Models\Valoracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ValoracionController extends Controller
{
    /**
     * Muestra una lista paginada de todas las valoraciones, con opción de búsqueda.
     */
    public function index(Request $request)
    {
        // Obtiene el término de búsqueda de la solicitud (query)
        $q = $request->input('q');

        // Comienza la consulta, ordenando por fecha y hora de forma descendente
        $query = Valoracion::orderBy('fecha_hora', 'desc');

        // Aplica el filtro de búsqueda si se proporciona un término
        if ($q) {
            $query->where(function ($subquery) use ($q) {
                $subquery->where('nombre_cliente', 'like', "%{$q}%")
                         ->orWhere('codigo', 'like', "%{$q}%")
                         ->orWhere('comentario', 'like', "%{$q}%");
            });
        }

        // Pagina los resultados
        $valoraciones = $query->paginate(10);

        // Retorna la vista con los datos
        return view('valoraciones.index', compact('valoraciones', 'q'));
    }

    /**
     * Muestra el formulario para crear una nueva valoración.
     */
    public function create()
    {
        return view('valoraciones.create');
    }

    /**
     * Almacena una nueva valoración en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos de entrada
        $request->validate([
            'nota_venta_id' => 'nullable|exists:nota_ventas,id',
            'nombre_cliente' => 'required|string|max:255',
            'experiencia_general' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // 2. Creación del código (se recomienda mover esta lógica al modelo o a un Servicio)
            $codigo = 'VAL-' . now()->timestamp . rand(100, 999);

            // 3. Creación del registro
            Valoracion::create([
                'codigo' => $codigo,
                'nota_venta_id' => $request->nota_venta_id,
                'nombre_cliente' => $request->nombre_cliente,
                'fecha_hora' => now(),
                'experiencia_general' => $request->experiencia_general,
                'comentario' => $request->comentario,
            ]);

            DB::commit();
            // 4. Redirección exitosa
            return redirect()->route('valoraciones.index')->with('success', 'Valoración creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // 5. Manejo de errores
            // Se elimina el mensaje de error detallado en producción por seguridad, usando uno genérico.
            return back()->with('error', 'Ocurrió un error inesperado al crear la valoración.')->withInput();
        }
    }

    /**
     * Muestra los detalles de una valoración específica usando Route Model Binding.
     */
    public function show(Valoracion $valoracion)
    {
        return view('valoraciones.show', compact('valoracion'));
    }

    /**
     * Muestra el formulario para editar una valoración existente usando Route Model Binding.
     */
    public function edit(Valoracion $valoracion)
    {
        return view('valoraciones.edit', compact('valoracion'));
    }

    /**
     * Actualiza una valoración existente en la base de datos.
     */
    public function update(Request $request, Valoracion $valoracion)
    {
        // 1. Validación de los datos de entrada
        $request->validate([
            'nota_venta_id' => 'nullable|exists:nota_ventas,id',
            'nombre_cliente' => 'required|string|max:255',
            'experiencia_general' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string|max:1000',
            // Valida que el código sea único, ignorando el ID actual del modelo
            'codigo' => ['required', Rule::unique('valoracions')->ignore($valoracion->id)],
            'fecha_hora' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // 2. Actualización del registro
            $valoracion->update([
                'codigo' => $request->codigo,
                'nota_venta_id' => $request->nota_venta_id,
                'nombre_cliente' => $request->nombre_cliente,
                'fecha_hora' => $request->fecha_hora,
                'experiencia_general' => $request->experiencia_general,
                'comentario' => $request->comentario,
            ]);

            DB::commit();
            // 3. Redirección exitosa
            return redirect()->route('valoraciones.index')->with('success', 'Valoración actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // 4. Manejo de errores
            return back()->with('error', 'Ocurrió un error inesperado al actualizar la valoración.')->withInput();
        }
    }

    /**
     * Elimina una valoración de la base de datos.
     */
    public function destroy(Valoracion $valoracion)
    {
        try {
            // Elimina el registro
            $valoracion->delete();
            return redirect()->route('valoraciones.index')->with('success', 'Valoración eliminada exitosamente.');
        } catch (\Exception $e) {
            // Manejo de errores (ej. si hay restricciones de llave foránea)
            return back()->with('error', 'Ocurrió un error al eliminar la valoración. Verifique si existen dependencias.');
        }
    }
}