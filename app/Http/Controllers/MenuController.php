<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Menu::query()->withCount('productos');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('cod_menu', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%")
                    ->orWhere('dia', 'like', "%{$q}%");
            });
        }

        $menus = $query->orderBy('cod_menu')->paginate(12)->withQueryString();

        return view('menus.index', compact('menus', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cod_menu' => ['required', 'string', 'max:50', 'unique:menus,cod_menu'],
            'precio' => ['required', 'numeric', 'min:0'],
            'dia' => ['nullable', 'string', 'max:50'],
            'descripcion' => ['nullable', 'string'],
        ]);

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menú creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load(['productos']);
        return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'cod_menu' => ['required', 'string', 'max:50', Rule::unique('menus', 'cod_menu')->ignore($menu->id)],
            'precio' => ['required', 'numeric', 'min:0'],
            'dia' => ['nullable', 'string', 'max:50'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menú actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Al eliminar, la FK en productos está definida con ON DELETE SET NULL (nullOnDelete),
        // por lo que no es necesario limpiar manualmente, pero podemos hacerlo si queremos.
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menú eliminado correctamente.');
    }
}