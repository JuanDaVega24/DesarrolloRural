<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProyectoProductivo;

class ProyectosTabla extends Component
{
    use WithPagination;

    // Filtros del servidor
    public $estado = '';
    public $ano = null; // Nuevo parámetro para filtrar por año

    protected $paginationTheme = 'bootstrap';

    public function mount($ano = null)
    {
        $this->ano = $ano;
    }

    // Reset pagination when filters change
    public function updated($propertyName)
    {
        if ($propertyName === 'estado') {
            $this->resetPage();
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['estado']);
        $this->resetPage();
    }

    public function render()
    {
        $proyectos = ProyectoProductivo::query()

            // === FILTRO POR AÑO (si se especifica) ===
            ->when($this->ano, fn($q) =>
                $q->where('ano', $this->ano)
            )

            // === FILTRO DEL SERVIDOR (estado) ===
            ->when($this->estado, fn($q) =>
                $q->where('estado', $this->estado)
            )

            // === ORDENAMIENTO ===
            ->latest()

            ->paginate(20);

        // Contar total de registros según filtros aplicados
        $queryTotal = ProyectoProductivo::query()
            ->when($this->ano, fn($q) => $q->where('ano', $this->ano));

        $totalRegistros = $queryTotal->count();

        return view('livewire.proyectos-tabla', [
            'proyectos' => $proyectos,
            'totalRegistros' => $totalRegistros
        ]);
    }
}
