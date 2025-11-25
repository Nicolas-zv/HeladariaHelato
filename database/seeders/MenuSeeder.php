<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $menus = [
            [
                'cod_menu' => 'G001',
                'precio' => 20.00,
                'dia' => 'Todos los días',
                'descripcion' => 'Tarrina de Gelato Pequeña (2 Sabores)',
            ],
            [
                'cod_menu' => 'G002',
                'precio' => 35.50,
                'dia' => 'Combo Semanal',
                'descripcion' => 'Copa Sundae Especial (3 bolas, nata, topping y salsa)',
            ],
            [
                'cod_menu' => 'G003',
                'precio' => 45.00,
                'dia' => 'Fin de Semana',
                'descripcion' => 'Combo Familiar: 1/2 Litro de Gelato + 4 Conos + 2 Toppings',
            ],
            [
                'cod_menu' => 'G004',
                'precio' => 28.00,
                'dia' => 'Todos los días',
                'descripcion' => 'Waffle o Crepe con 1 bola de Gelato y sirope a elegir',
            ],
            [
                'cod_menu' => 'G005',
                'precio' => 15.00,
                'dia' => 'Todos los días',
                'descripcion' => 'Affogato Clásico (Espresso con 1 bola de Gelato de Vainilla)',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(['cod_menu' => $menu['cod_menu']], $menu);
        }
    }
}
