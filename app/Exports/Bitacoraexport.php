<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Bitacoraexport implements FromCollection, WithHeadings
{
    protected $search;
    protected $startDate;
    protected $endDate;

    // Constructor para recibir los filtros
    public function __construct($search, $startDate, $endDate)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    // Filtrar los datos según los parámetros recibidos
    public function collection()
    {
        $query = Activity::query();

        // Filtrar por búsqueda
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('causer', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtrar por fechas
        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // Obtener los resultados
        return $query->get([
            'id',
            'causer_id',  // asumiendo que tienes una relación con el usuario
            'description',
            'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Usuario',
            'Actividad',
            'Fecha',
        ];
    }
}

