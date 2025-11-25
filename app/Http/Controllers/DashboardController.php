<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Ventas;
use App\Models\producto;
use App\Models\User;
use App\Models\cliente;
use App\Models\Compras;
use App\Models\Detalleventas;
use App\Models\Gastos;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        return view('pages/dashboard/dashboard');
    }


    /**
     * Displays the analytics screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function analytics()
    {
        return view('pages/dashboard/analytics');
    }

    /**
     * Displays the fintech screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fintech()
    {
        return view('pages/dashboard/fintech');
    }
}
